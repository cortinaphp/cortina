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
namespace Cortina\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Stack runner for calling middleware
 */
class StackRunner
{

    /**
     * Stack of middleware
     * @var \Cortina\Middleware\Stack
     */
    protected $stack;

    /**
     * Current stack layer position
     * @var integer
     */
    protected $position;

    /**
     * Create the Stack Runner with a Stack of middleware
     * @param \Cortina\Middleware\Stack $stack
     */
    public function __construct(Stack $stack)
    {
        $this->position = 0;
        $this->stack = $stack;
    }

    /**
     * Invoke the middleware instances
     * @param  ServerRequestInterface  $request
     * @param  ResponseInterface $response
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response)
    {
        $middleware = $this->stack->top();
        if ($middleware) {
            $this->stack->remove($middleware);
            return $middleware($request, $response, $this);
        }

        return $response;
    }
}
