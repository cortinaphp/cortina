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
namespace Cortina\Test\TestCase\Middleware;

use Cortina\Middleware\Stack;
use PHPUnit\Framework\TestCase;

/**
 * Stack Test
 */
class StackTest extends TestCase
{

    /**
     * Test Get method
     * @return void
     */
    public function testGet()
    {
        $middlewareOne = function ($request, $response, $next) {
            $a = time();
            return $next($request, $response);
        };
        $middlewareTwo = function ($request, $response, $next) {
            $b = time();
            return $next($request, $response);
        };
        $middlewareThree = function ($request, $response, $next) {
            $c = time();
            return $next($request, $response);
        };

        $stack = new Stack();
        $stack->add($middlewareOne);
        $stack->add($middlewareTwo);
        $stack->add($middlewareThree);

        $this->assertSame($middlewareOne, $stack->get(0));
        $this->assertSame($middlewareTwo, $stack->get(1));
        $this->assertSame($middlewareThree, $stack->get(2));
    }

    /**
     * Test Add method
     * @return void
     */
    public function testAdd()
    {
        $middleware = function ($request, $response, $next) {
            $a = time();
            return $next($request, $response);
        };
        $stack = new Stack();
        $result = $stack->add($middleware);
        $this->assertSame($stack, $result);
    }

    /**
     * Test remove method
     * @return void
     */
    public function testRemove()
    {
        $middlewareOne = function ($request, $response, $next) {
            $a = time();
            return $next($request, $response);
        };
        $middlewareTwo = function ($request, $response, $next) {
            $b = time();
            return $next($request, $response);
        };
        $stack = new Stack();
        $stack->add($middlewareOne);
        $stack->add($middlewareTwo);
        $this->assertSame($middlewareOne, $stack->get(0));
        $stack->remove($middlewareOne);
        $this->assertSame($middlewareTwo, $stack->get(0));
    }

}
