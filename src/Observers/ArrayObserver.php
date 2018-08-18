<?php

namespace PhpSchema\Observers;

use ArrayObject;
use PhpSchema\Contracts\Arrayable;
use PhpSchema\Contracts\Verifiable;

class ArrayObserver extends ArrayObject implements Arrayable
{
    protected $parent;

    public function __construct(array $input = [], Verifiable $parent) {
        $this->parent = $parent;
        parent::__construct($input);
    }

    public function offsetSet($offset, $value) {
        parent::offsetSet($offset, $value);
        $this->parent->validate();
    }

    public function offsetUnset($offset) {
        parent::offsetUnset($offset);
        $this->parent->validate();
    }

    public function toArray(): array
    {
        return $this->getArrayCopy();
    }
}