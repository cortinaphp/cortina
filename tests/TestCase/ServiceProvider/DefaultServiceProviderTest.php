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
namespace Cortina\Test\TestCase\ServiceProvider;

use Cortina\ServiceProvider\DefaultServiceProvider;
use PHPUnit\Framework\TestCase;
use Cortina\Container\Container;

/**
 * Default Service Provider Test
 */
class DefaultServiceProviderTest extends TestCase
{

    /**
     * Test register services
     * @return void
     */
    public function testClass()
    {
        $container = new Container();
        $defaultServiceProvider = new DefaultServiceProvider();
        $defaultServiceProvider->setContainer($container);
        $defaultServiceProvider->register();

        $defaultServices = [
            'request' => 'Psr\Http\Message\RequestInterface',
            'response' => 'Psr\Http\Message\ResponseInterface',
            'emitter' => 'Cortina\Network\SapiEmitter',
            'router' => 'FastRoute\RouteCollector',
        ];

        foreach ($defaultServices as $serviceKey => $class) {
            $this->assertTrue(
                $container->has($serviceKey),
                sprintf('Default service "%s" has not been registered', $serviceKey)
            );

            $this->assertInstanceOf($class, $container->get($serviceKey));
        }
    }

}
