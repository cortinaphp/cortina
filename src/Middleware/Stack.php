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
namespace Cortina\Middleware;

/**
 * Stack for middleware
 */
class Stack
{

    /**
     * Middlewares callable
     * @var array
     */
    private $middleware = [];

    /**
     * Get top of the stack and remove
     * @return callable|null
     */
    public function top()
    {
        return $this->get($this->count() - 1);
    }


    /**
     * Get middleware with index
     * @param  integer $index
     * @return callable|null
     */
    public function get($index)
    {
        if (isset($this->middleware[$index])) {
            return $this->middleware[$index];
        }
        return null;
    }

    /**
     * Get index of middleware
     * @param  callable $middleware
     * @return integer|null
     */
    public function getIndex(callable $middleware)
    {
        return array_search($middleware, $this->middleware);
    }

    /**
     * Prepend middleware to the stack
     * @param callable $middleware
     * @return $this
     */
    public function prepend(callable $middleware)
    {
        $key = array_search($middleware, $this->middleware);
        if ($key === false) {
            array_unshift($this->middleware, $middleware);
        }
        return $this;
    }

    /**
     * Append middleware to the stack
     * @param callable $middleware
     * @return $this
     */
    public function append(callable $middleware)
    {
        $key = array_search($middleware, $this->middleware);
        if ($key === false) {
            $this->middleware[] = $middleware;
        }
        return $this;
    }

    /**
     * Remove midleware from the stack and rebase the keys
     * so the middleware stack start from key 0
     * @param  callable $middleware
     * @return $this
     */
    public function remove(callable $middleware)
    {
        $key = array_search($middleware, $this->middleware);
        if ($key !== false) {
            unset($this->middleware[$key]);
            $this->middleware = array_values($this->middleware);
        }
        return $this;
    }

    /**
     * Count middleware
     * @return integer
     */
    public function count()
    {
        return count($this->middleware);
    }
}
