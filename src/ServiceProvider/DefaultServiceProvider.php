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
namespace Cortina\ServiceProvider;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Zend\Diactoros\ServerRequestFactory;
/**
 * Default Service Provider for Cortina
 */
class DefaultServiceProvider extends AbstractServiceProvider
{

    /**
     * Services $this provides
     * @var array
     */
    protected $provides = [
        'request',
        'response',
        'emitter',
        'router',
    ];

    /**
     * Register services
     * @return void
     */
    public function register()
    {
        $this->getContainer()->share('request', function () {
            return ServerRequestFactory::fromGlobals(
                $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES
            );
        });
        $this->getContainer()->share('response', 'Zend\Diactoros\Response');
        $this->getContainer()->share('emitter', 'Zend\Diactoros\Response\SapiEmitter');
        $this->getContainer()->add('routeParser', 'FastRoute\RouteParser\Std');
        $this->getContainer()->add('dataGenerator', 'FastRoute\\DataGenerator\\GroupCountBased');
        $this->getContainer()->add('dispatcher', 'FastRoute\\Dispatcher\\GroupCountBased');
        $this->getContainer()->add('router', 'FastRoute\RouteCollector')
            ->withArgument($this->getcontainer()->get('routeParser'))
            ->withArgument($this->getcontainer()->get('dataGenerator'));
    }
}
