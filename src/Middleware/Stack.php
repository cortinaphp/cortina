<?php
namespace Cortina\Middleware;

class Stack
{

    /**
     * Middlewares callable
     * @var array
     */
    private $middleware = [];

    /**
     * Get middleware with index
     * @param  integer $index
     * @return callable|false
     */
    public function get($index)
    {
        if (isset($this->middleware[$index])) {
            return $this->middleware[$index];
        }
        return false;
    }


    public function add($middleware)
    {
        $key = array_search($middleware, $this->middleware);
        if ($key === false) {
            $this->middleware[] = $middleware;
        }
        return $this;
    }

    public function remove($middleware)
    {
        $key = array_search($middleware, $this->middleware);
        if ($key !== false) {
            unset($this->middleware[$key]);
        }
        return $this;
    }

}
