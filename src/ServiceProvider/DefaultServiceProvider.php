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
        $this->getContainer()->share('request', 'Cortina\Network\Request');
        $this->getContainer()->share('response', 'Cortina\Network\Response');
        $this->getContainer()->share('emitter', 'Cortina\Network\SapiEmitter');
        $this->getContainer()->add('router', 'FastRoute\RouteCollector')
            ->withArgument(new \FastRoute\RouteParser\Std)
            ->withArgument(new \FastRoute\DataGenerator\GroupCountBased);
    }
}
