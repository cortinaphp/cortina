<?php

namespace Cortina\Middleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Stack
{

    /**
     * Middlewares callable
     * @var array
     */
    protected $middlewares = [];

    /**
     * Current Middleware Stack position
     * @var integer
     */
    protected $current = 0;

    /**
     * Return an instance with the specified middleware added to the stack.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the stack, and MUST return an instance that contains
     * the specified middleware.
     *
     * @param MiddlewareInterface $middleware
     *
     * @return self
     */
    public function withMiddleware($middleware)
    {
        $stack = clone $this;
        $key = array_search($middleware, $stack->middlewares);
        if ($key === false) {
            $stack->middlewares[] = $middleware;
        }
        return $stack;
    }

    /**
     * Return an instance without the specified middleware.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the stack, and MUST return an instance that does not
     * contain the specified middleware.
     *
     * @param MiddlewareInterface $middleware
     *
     * @return self
     */
    public function withoutMiddleware($middleware)
    {
        $stack = clone $this;
        $key = array_search($middleware, $stack->middlewares);
        if ($key !== false) {
            unset($stack->middlewares[$key]);
        }
        return $stack;
    }

    /**
     * Process the request through middleware and return the response.
     *
     * This method MUST be implemented in such a way as to allow the same
     * stack to be reused for processing multiple requests in sequence.
     *
     * @param RequestInterface $request
     *
     * @return ResponseInterface
     */
    public function process(RequestInterface $request, ResponseInterface $response)
    {
        return $this->__invoke($request, $response);
    }

    /**
     * Invoke the middleware instances
     * @param  RequestInterface  $request
     * @param  ResponseInterface $response
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(RequestInterface $request, ResponseInterface $response)
    {
        if (isset($this->middlewares[$this->current])) {
            $middleware = $this->middlewares[$this->current];
            if ($middleware) {

                $this->current++;
                return $middleware($request, $response, $stack);
            }
        }

        return $response;
    }

}
