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

/**
 * App Test
 */
class AppTest extends TestCase
{

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
     * Test App construct with Container
     * @return void
     */
    public function testConstructWithContainer()
    {
        $container = new Container();
        $app = new App($container);
        $this->assertSame(
            $app->getContainer(),
            $container,
            'App can\'t be constructed with a container'
        );
    }

}
