<?php

namespace PhpSchema\Observers;

use ArrayAccess, Countable, Iterator;
use PhpSchema\Traits\Loopable;
use PhpSchema\Contracts\Observable;
use PhpSchema\Traits\ArrayAccessible;

class ArrayObserver extends Observer implements ArrayAccess, Countable, Iterator
{
    use ArrayAccessible, Loopable;

    public function count()
    {
        return count($this->container);
    }
}