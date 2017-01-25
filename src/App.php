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
use Cortina\Middleware\Stack;
use Cortina\ServiceProvider\DefaultServiceProvider;
use Interop\Container\ContainerInterface;

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
     * Run Cortina
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function run($silentMode)
    {
        $routeInfo = $this->dispatcher->dispatch(
            $this->request->getMethod(),
            $this->request->getUri()->getPath()
        );
        switch ($routeInfo[0]) {
            case $this->dispatcher::NOT_FOUND:
                return $this->emitter->safeEmit(
                    $this->response->withStatus(404)->getBody()->write('Not found'),
                    null,
                    $silentMode
                );
            case $this->dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];
                return $this->emitter->safeEmit(
                    $this->response->withStatus(405)->getBody()->write('Method not allowed'),
                    null,
                    $silentMode
                );
                break;
            case $this->dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];
                $response = $handler($this->request, $this->response, $vars);

                $stack = new Stack();
                $stack->withMiddleware(new App());

                $response = $stack->process($this->request, $response);

                return $this->emitter->safeEmit(
                    $response,
                    null,
                    $silentMode
                );
        }

        return (string)$response->getBody();

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
