<?php
namespace Cortina\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class StackRunner
{

    protected $stack;

    protected $position;

    public function __construct(Stack $stack)
    {
        $this->position = 0;
        $this->stack = $stack;
    }

    public function process(ServerRequestInterface $request, ResponseInterface $response)
    {
        $this->stack = $stack;
        return $this->__invoke($request, $response);
    }

    /**
     * Invoke the middleware instances
     * @param  RequestInterface  $request
     * @param  ResponseInterface $response
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response)
    {
        $middleware = $this->stack->get($this->position);
        if ($middleware) {
            $this->position++;
            return $middleware($request, $response, $this);
        }

        return $response;
    }

}
