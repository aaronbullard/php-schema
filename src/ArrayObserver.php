<?php

namespace PhpSchema;

use PhpSchema\Contracts\Arrayable;

class ArrayObserver implements Arrayable, \ArrayAccess, \Iterator
{
    protected $container = [];

    protected $postion;

    protected $parent;

    public function __construct(array $data = [], Model $parent) {
        $this->container = $data;
        $this->position = 0;
        $this->parent = $parent;
    }

    public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }

        $this->parent->validate();
    }

    public function offsetExists($offset) {
        return isset($this->container[$offset]);
    }

    public function offsetUnset($offset) {
        unset($this->container[$offset]);
        $this->parent->validate();
    }

    public function offsetGet($offset) {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }

    // Iterable

    public function rewind() {
        $this->position = 0;
    }

    public function current() {
        return $this->container[$this->position];
    }

    public function key() {
        return $this->position;
    }

    public function next() {
        ++$this->position;
    }

    public function valid() {
        return isset($this->container[$this->position]);
    }

    public function toArray(): array
    {
        return $this->container;
    }
}