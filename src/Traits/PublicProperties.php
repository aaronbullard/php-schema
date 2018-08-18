<?php

namespace PhpSchema\Traits;

trait PublicProperties
{
    public function __set($key, $value)
    {
        $this->setAttribute($key, $value);
    }

    public function __get($key)
    {
        return $this->getAttribute($key);
    }
}