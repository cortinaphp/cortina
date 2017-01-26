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
namespace Cortina\Network;

use FastRoute\Dispatcher\GroupCountBased as FastRouteDispatcher;
use FastRoute\RouteCollector;

/**
 * Default Dispatcher
 */
class Dispatcher extends FastRouteDispatcher
{

    public function __construct(RouteCollector $router)
    {
        parent::__construct($router->getData());
    }

}
