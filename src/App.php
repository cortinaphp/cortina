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
    public function __construct(ContainerInterface $container = null)
    {
        if (isset($container)) {
            $this->container = $container;
        } else {
            $this->container = new Container();
        }

        $this->container->addServiceProvider(new DefaultServiceProvider);
    }

    /**
     * Get Container
     * @return \Interop\Container\ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Magic method for getting services if container has them
     * @param  string $param
     * @return mixed
     */
    public function __get(string $param)
    {
        if (isset($this->$param)) {
            return $this->$param;
        }
        if ($this->container->has($param)) {
            return $this->container->get($param);
        }
    }

}
