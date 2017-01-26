<?php
/**
 * Cortina : PHP Framework
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Ross Chater. (http://rosschater.com)
 * @link          http://rosschater.com Project Cortina
 * @since         0.0.1
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace Cortina;

use Cortina\Container\Container;
use Cortina\Middleware\StackRunner;
use Cortina\ServiceProvider\DefaultServiceProvider;
use Interop\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * App
 */
class App
{

    /**
     * Container
     * @var \Interop\Container\ContainerInterface
     */
    protected $container;

    /**
     * Create new App
     * @param
     */
    public function __construct(ContainerInterface $container = null)
    {
        if (isset($container)) {
            $this->container = $container;
        } else {
            $this->container = new Container();
        }

        $this->container->addServiceProvider(new DefaultServiceProvider);
    }

    /**
     * Invoke App
     * @param  RequestInterface  $request
     * @param  ResponseInterface $response
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response)
    {
        $routeInfo = $this->dispatcher->dispatch(
            $request->getMethod(),
            $request->getUri()->getPath()
        );
        switch ($routeInfo[0]) {
            case $this->dispatcher::NOT_FOUND:
                $response = $response->withStatus(404);
                $response->getBody()->write('Not found');
                break;
            case $this->dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];
                $response = $response->withStatus(405);
                $response->getBody()->write('Method not allowed');
                break;
            case $this->dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];
                $response = $handler($request, $response, $vars);
        }

        return $response;
    }

    /**
     * Magic method for getting services if container has them
     * @param  string $param
     * @return mixed
     */
    public function __get(string $param)
    {
        if (isset($this->$param)) {
            return $this->$param;
        }
        if ($this->container->has($param)) {
            return $this->container->get($param);
        }
    }

    /**
     * Add middleware to cloned app and return
     * @param  callable     $middleware
     * @return \Cortina\App $app
     */
    public function withMiddleware(callable $middleware)
    {
        $app = clone $this;
        $app->stack->add($middleware);
        return $app;
    }

    /**
     * Remove middleware from cloned app and return
     * @param  callable     $middleware
     * @return \Cortina\App $app
     */
    public function withoutMiddleware(callable $middleware)
    {
        $app = clone $this;
        $app->stack->remove($middleware);
        return $app;
    }

    /**
     * Run Cortina
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function run($silentMode = false)
    {
        // Add \Cortina\App as final invoked layer of middleware
        // which fires off the routed handler
        $this->stack->add($this);
        $this->stack->add(new \Franzl\Middleware\Whoops\ErrorMiddleware);

        $runner = new StackRunner($this->stack);
        return $this->emitter->safeEmit(
            $runner($this->request, $this->response),
            null,
            $silentMode
        );
    }

    /**
     * Get Container
     * @return \Interop\Container\ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Add GET route to router
     * @param  string   $path     e.g. "/profile/:name"
     * @param  callable $callable Handler for route
     * @return void
     */
    public function get(string $path, callable $callable)
    {
        $this->router->addRoute('GET', $path, $callable);
    }

    /**
     * Add POST route to router
     * @param  string   $path     e.g. "/profiles"
     * @param  callable $callable Handler for route
     * @return void
     */
    public function post(string $path, callable $callable)
    {
        $this->router->addRoute('POST', $path, $callable);
    }

    /**
     * Add PUT route to router
     * @param  string   $path     e.g. "/profiles/:name"
     * @param  callable $callable Handler for route
     * @return void
     */
    public function put(string $path, callable $callable)
    {
        $this->router->addRoute('PUT', $path, $callable);
    }

    /**
     * Add PATCH route to router
     * @param  string   $path     e.g. "/profiles/:name"
     * @param  callable $callable Handler for route
     * @return void
     */
    public function patch(string $path, callable $callable)
    {
        $this->router->addRoute('PATCH', $path, $callable);
    }

    /**
     * Add DELETE route to router
     * @param  string   $path     e.g. "/profiles/:name"
     * @param  callable $callable Handler for route
     * @return void
     */
    public function delete(string $path, callable $callable)
    {
        $this->router->addRoute('DELETE', $path, $callable);
    }

}
