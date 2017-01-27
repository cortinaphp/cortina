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
namespace Cortina\Test\TestCase;

use Cortina\Engine;
use Cortina\Network\Request;
use PHPUnit\Framework\TestCase;
use Cortina\Container\Container;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Engine Test
 */
class EngineTest extends TestCase
{

    /**
     * Test Engine construct with Container
     */
    public function testConstructContainer()
    {
        $container = new Container();
        $engine = new Engine($container);
        $this->assertInstanceOf('Interop\Container\ContainerInterface', $engine->getContainer());
    }

    /**
     * Test Engine construct with Container
     * @expectedException TypeError
     */
    public function testConstructContaineryException()
    {
        $invalidContainer = new \stdClass();
        $engine = new Engine($invalidContainer);
    }

    /**
     * Test getContainer() method
     * @return void
     */
    public function testGetContainer()
    {
        $engine = new Engine();
        $this->assertInstanceOf('Interop\Container\ContainerInterface', $engine->getContainer());
    }

    /**
     * Test with Middleware
     * @return void
     */
    public function testWithMiddleware()
    {
        $geoLocateMiddleware = function (ServerRequestInterface $request, ResponseInterface $response, callable $next) {
            $request = $request->withAttribute('GEOLOCATE', 'LONDON');
            return $next($request, $response);
        };

        $engine = new Engine();
        $engine->request = new Request([], [], '/city', 'GET');

        $geoLocateValue = '';

        $engine->get(
            '/city',
            function (ServerRequestInterface $request, ResponseInterface $response) use (&$geoLocateValue) {
                $geoLocateValue = $request->getAttribute('GEOLOCATE');
                return $response;
            }
        );

        $engine
            ->withMiddleware($geoLocateMiddleware)
            ->start(true);

        $this->assertEquals('LONDON', $geoLocateValue);
    }

    /**
     * Test get container response
     * @return void
     */
    public function testGetContainerServiceByMagic()
    {
        $engine = new Engine();
        $this->assertInstanceOf(
            '\Psr\Http\Message\ResponseInterface',
            $engine->response,
            'Engine can\'t magic __get container services'
        );
    }

    /**
     * Test get container request
     * @return void
     */
    public function testGetContainerRequest()
    {
        $engine = new Engine();
        $this->assertInstanceOf(
            '\Psr\Http\Message\RequestInterface',
            $engine->getContainer()->get('request'),
            'Container does not contain valid RequestInterface instance'
        );
    }

    /**
     * Test get container response
     * @return void
     */
    public function testGetContainerResponse()
    {
        $engine = new Engine();
        $this->assertInstanceOf(
            '\Psr\Http\Message\ResponseInterface',
            $engine->getContainer()->get('response'),
            'Container does not contain valid ResponseInterface instance'
        );
    }

    /**
     * Test add GET Route
     * @return void
     */
    public function testAddGetRoute()
    {
        $handler = function (ServerRequestInterface $request, ResponseInterface $response) {
            return $response;
        };

        $engine = new Engine();
        $engine->get('/', $handler);

        $expected = [
            [
                'GET' => [
                    '/' => $handler
                ],
            ],
            []
        ];
        $this->assertSame($engine->router->getData(), $expected, 'Can\'t add route as expected');
    }

    /**
     * Test add POST Route
     * @return void
     */
    public function testAddPostRoute()
    {
        $handler = function (ServerRequestInterface $request, ResponseInterface $response) {
            return $response;
        };

        $engine = new Engine();
        $engine->post('/people', $handler);

        $expected = [
            [
                'POST' => [
                    '/people' => $handler
                ],
            ],
            []
        ];
        $this->assertSame($engine->router->getData(), $expected, 'Can\'t add route as expected');
    }

    /**
     * Test add PUT Route
     * @return void
     */
    public function testAddPutRoute()
    {
        $handler = function (ServerRequestInterface $request, ResponseInterface $response) {
            return $response;
        };

        $engine = new Engine();
        $engine->put('/people/:name', $handler);

        $expected = [
            [
                'PUT' => [
                    '/people/:name' => $handler
                ],
            ],
            []
        ];
        $this->assertSame($engine->router->getData(), $expected, 'Can\'t add route as expected');
    }

    /**
     * Test add PATCH Route
     * @return void
     */
    public function testAddPatchRoute()
    {
        $handler = function (ServerRequestInterface $request, ResponseInterface $response) {
            return $response;
        };

        $engine = new Engine();
        $engine->patch('/people/:name', $handler);

        $expected = [
            [
                'PATCH' => [
                    '/people/:name' => $handler
                ],
            ],
            []
        ];
        $this->assertSame($engine->router->getData(), $expected, 'Can\'t add route as expected');
    }

    /**
     * Test add DELETE Route
     * @return void
     */
    public function testAddDeleteRoute()
    {
        $handler = function (ServerRequestInterface $request, ResponseInterface $response) {
            return $response;
        };

        $engine = new Engine();
        $engine->delete('/people/:name', $handler);

        $expected = [
            [
                'DELETE' => [
                    '/people/:name' => $handler
                ],
            ],
            []
        ];
        $this->assertSame($engine->router->getData(), $expected, 'Can\'t add route as expected');
    }

    /**
     * Test Run
     * @return void
     */
    public function testRun()
    {
        $request = new Request([], [], '/test', 'GET');
        $engine = new Engine();
        $engine->request = $request;

        $engine->get('/test', function (ServerRequestInterface $request, ResponseInterface $response) {
            $response->getBody()->write('Hello World!');
            return $response;
        });
        $response = $engine->start(true);

        $output = (string)$response;
        $this->assertEquals($output, 'Hello World!');
    }
}
