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

namespace Cortina;

use League\Container\Container;
use League\Container\ReflectionContainer;

/**
 * App
 */
class App
{

    /**
     * Container
     * @var Interop\Container\ContainerInterface
     */
    protected $_container;

    /**
     * Create new App
     */
    public function __construct()
    {
        $this->_container = new Container();
    }

    /**
     * Get Container
     * @return \Psr\Container\ContainerInterface
     */
    public function getContainer()
    {
        return $this->_container;
    }

}
