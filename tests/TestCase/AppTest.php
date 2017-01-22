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

use Cortina\App;
use Cortina\Network\Request;
use PHPUnit\Framework\TestCase;
use Cortina\Container\Container;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
/**
 * App Test
 */
class AppTest extends TestCase
{

    /**
     * Test App construct with Container
     */
    public function testConstructContainer()
    {
        $container = new Container();
        $app = new App($container);
        $this->assertInstanceOf('Interop\Container\ContainerInterface', $app->getContainer());
    }

    /**
     * Test App construct with Container
     * @expectedException TypeError
     */
    public function testConstructContaineryException()
    {
        $invalidContainer = new \stdClass();
        $app = new App($invalidContainer);
    }

    /**
     * Test getContainer() method
     * @return void
     */
    public function testGetContainer()
    {
        $app = new App();
        $this->assertInstanceOf('Interop\Container\ContainerInterface', $app->getContainer());
    }

    /**
     * Test get container response
     * @return void
     */
    public function testGetContainerServiceByMagic()
    {
        $app = new App();
        $this->assertInstanceOf(
            '\Psr\Http\Message\ResponseInterface',
            $app->response,
            'App can\'t magic __get container services'
        );
    }

    /**
     * Test get container request
     * @return void
     */
    public function testGetContainerRequest()
    {
        $app = new App();
        $this->assertInstanceOf(
            '\Psr\Http\Message\RequestInterface',
            $app->getContainer()->get('request'),
            'Container does not contain valid RequestInterface instance'
        );
    }

    /**
     * Test get container response
     * @return void
     */
    public function testGetContainerResponse()
    {
        $app = new App();
        $this->assertInstanceOf(
            '\Psr\Http\Message\ResponseInterface',
            $app->getContainer()->get('response'),
            'Container does not contain valid ResponseInterface instance'
        );
    }

    /**
     * Test add GET Route
     * @return void
     */
    public function testAddGetRoute()
    {
        $handler = function (RequestInterface $request, ResponseInterface $response) {
            return $response;
        };

        $app = new App();
        $app->get('/', $handler);

        $expected = [
            [
                'GET' => [
                    '/' => $handler
                ],
            ],
            []
        ];
        $this->assertSame($app->router->getData(), $expected, 'Can\'t add route as expected');
    }

    /**
     * Test add POST Route
     * @return void
     */
    public function testAddPostRoute()
    {
        $handler = function (RequestInterface $request, ResponseInterface $response) {
            return $response;
        };

        $app = new App();
        $app->post('/people', $handler);

        $expected = [
            [
                'POST' => [
                    '/people' => $handler
                ],
            ],
            []
        ];
        $this->assertSame($app->router->getData(), $expected, 'Can\'t add route as expected');
    }

    /**
     * Test add PUT Route
     * @return void
     */
    public function testAddPutRoute()
    {
        $handler = function (RequestInterface $request, ResponseInterface $response) {
            return $response;
        };

        $app = new App();
        $app->put('/people/:name', $handler);

        $expected = [
            [
                'PUT' => [
                    '/people/:name' => $handler
                ],
            ],
            []
        ];
        $this->assertSame($app->router->getData(), $expected, 'Can\'t add route as expected');
    }

    /**
     * Test add PATCH Route
     * @return void
     */
    public function testAddPatchRoute()
    {
        $handler = function (RequestInterface $request, ResponseInterface $response) {
            return $response;
        };

        $app = new App();
        $app->patch('/people/:name', $handler);

        $expected = [
            [
                'PATCH' => [
                    '/people/:name' => $handler
                ],
            ],
            []
        ];
        $this->assertSame($app->router->getData(), $expected, 'Can\'t add route as expected');
    }

    /**
     * Test add DELETE Route
     * @return void
     */
    public function testAddDeleteRoute()
    {
        $handler = function (RequestInterface $request, ResponseInterface $response) {
            return $response;
        };

        $app = new App();
        $app->delete('/people/:name', $handler);

        $expected = [
            [
                'DELETE' => [
                    '/people/:name' => $handler
                ],
            ],
            []
        ];
        $this->assertSame($app->router->getData(), $expected, 'Can\'t add route as expected');
    }

    /**
     * Test Run
     * @return void
     */
    public function testRun()
    {
        $request = new Request('/test', 'GET');
        $app = new App();
        $app->getContainer()->add('request', $request);

        $app->get('/test', function (RequestInterface $request, ResponseInterface $response) {
            $response->getBody()->write('Hello World!');
            return $response;
        });
        $response = $app->run(true);
        $output = (string)$response;
        $this->assertEquals($output, 'Hello World!');
    }
}
