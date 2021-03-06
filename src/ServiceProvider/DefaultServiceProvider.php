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

use Cortina\Network\ServerRequestFactory;
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
        'dispatcher',
        'stack',
    ];

    /**
     * Register services
     * @return void
     */
    public function register()
    {
        $this->getContainer()->share('request', ServerRequestFactory::fromGlobals());
        $this->getContainer()->share('response', 'Cortina\Network\Response');
        $this->getContainer()->share('emitter', 'Cortina\Network\SapiEmitter');
        $this->getContainer()->share('router', 'FastRoute\RouteCollector')
            ->withArgument(new \FastRoute\RouteParser\Std)
            ->withArgument(new \FastRoute\DataGenerator\GroupCountBased);
        $this->getContainer()->share('dispatcher', 'Cortina\Network\Dispatcher')
            ->withArgument($this->getContainer()->get('router'));
        $this->getContainer()->share('stack', 'Cortina\Middleware\Stack');
    }
}
