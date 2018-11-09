<?php

namespace PhpSchema\Observers;

use ArrayAccess, Countable, Closure, Iterator;
use PhpSchema\Traits\Loopable;
use PhpSchema\Contracts\Observable;
use PhpSchema\Traits\ArrayAccessible;

class ArrayObserver extends Observer implements ArrayAccess, Countable, Iterator
{
    use ArrayAccessible, Loopable;

    public function push($item)
    {
        $this[] = $item;
        
        return $this;
    }

    public function count()
    {
        return count($this->container);
    }

    public function map(Closure $fn)
    {
        return array_map($fn, $this->container);
    }

    public function filter(Closure $fn)
    {
        return array_filter($this->container, $fn, ARRAY_FILTER_USE_BOTH);
    }

    public function reduce(Closure $fn, $initial = null)
    {
        return array_reduce($this->container, $fn, $initial);
    }
}
