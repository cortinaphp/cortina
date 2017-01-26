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
use Cortina\Middleware\StackRunner;
use Cortina\Network\Request;
use Cortina\Network\Response;
use PHPUnit\Framework\TestCase;

/**
 * Stack Test
 */
class StackRunnerTest extends TestCase
{

    /**
     * Test runner with middleware stack
     * and make sure all layers of middleware are
     * returning as expected
     *
     * @return void
     */
    public function testRunner()
    {
        $middlewareOne = function ($request, $response, $next) {
            return $next($request, $response->withHeader('something', 'nice'));
        };
        $middlewareTwo = function ($request, $response, $next) {
            return $next($request, $response->withStatus(429));
        };
        $middlewareThree = function ($request, $response, $next) {
            $response->getBody()->write('Testing toon army');
            return $next($request, $response);
        };
        $stack = new Stack();
        $stack->add($middlewareOne);
        $stack->add($middlewareTwo);
        $stack->add($middlewareThree);

        $runner = new StackRunner($stack);

        $response = $runner(new Request(), new Response());

        $this->assertEquals('Testing toon army', (string)$response->getBody());
        $this->assertEquals(429, $response->getStatusCode());
        $this->assertEquals('nice', $response->getHeaderLine('something'));
    }
}
