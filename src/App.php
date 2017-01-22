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

use \InvalidArgumentException;
use Cortina\ServiceProvider\DefaultServiceProvider;
use Interop\Container\ContainerInterface;
use League\Container\Container;
use League\Container\Definition\DefinitionFactoryInterface;
use League\Container\ReflectionContainer;

/**
 * App
 */
class App
{

    /**
     * Container
     * @var \Interop\Container\ContainerInterface
     */
    protected $container;

    /**
     * Create new App
     * @param
     */
    public function __construct($definitionFactory = null)
    {
        if (isset($definitionFactory) && !($definitionFactory instanceof DefinitionFactoryInterface)) {
            $message = 'An invalid DefinitionFactory has been supplied';
            throw new \InvalidArgumentException($message);
        }
        $this->container = new Container(null, null, $definitionFactory);
        $this->getContainer()
            ->addServiceProvider(new DefaultServiceProvider);
    }

    /**
     * Get Container
     * @return \Interop\Container\ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

}
