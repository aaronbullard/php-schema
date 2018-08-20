<?php

namespace PhpSchema\Traits;

trait Iterates
{
    protected $position = 0;

    // Iterator
    public function rewind()
    {
        $this->position = 0;
    }

    public function current()
    {
        $key = $this->key();
        return $this->obj->$key;
    }

    public function key()
    {
        $keys = array_values(
            get_object_vars($this->obj)
        );

        return $keys[$this->position];
    }

    public function next()
    {
        ++$this->position;
    }

    public function valid() {
        $value = $this->key();
        return isset( $value );
    }
}