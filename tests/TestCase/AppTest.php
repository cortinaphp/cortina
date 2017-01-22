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
use PHPUnit\Framework\TestCase;
use League\Container\Container;
use League\Container\Definition\DefinitionFactory;

/**
 * App Test
 */
class AppTest extends TestCase
{

    /**
     * Test App construct with Container
     */
    public function testConstructDefinitionFactory()
    {
        $definitionFactory = new DefinitionFactory();
        $app = new App($definitionFactory);
        $this->assertInstanceOf('Interop\Container\ContainerInterface', $app->getContainer());
    }

    /**
     * Test App construct with Container
     * @expectedException InvalidArgumentException
     */
    public function testConstructDefinitionFactoryException()
    {
        $invalidDefinitionFactory = new \stdClass();
        $app = new App($invalidDefinitionFactory);
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
     * Test get container request
     * @return void
     */
    public function testGetContainerRequest()
    {
        $app = new App();
        $this->assertInstanceOf(
            '\Psr\Http\Message\RequestInterface',
            $app->getContainer()->get('Request'),
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
            $app->getContainer()->get('Response'),
            'Container does not contain valid ResponseInterface instance'
        );
    }

}
