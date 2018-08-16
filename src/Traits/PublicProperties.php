<?php

namespace PhpSchema\Traits;

trait PublicProperties
{
    public function __set($key, $value)
    {
        $this->set($key, $value);

        $this->validate();
    }

    public function __get($key)
    {
        return $this->_attributes[$key];
    }
}