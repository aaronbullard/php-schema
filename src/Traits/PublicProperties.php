<?php

namespace PhpSchema\Traits;

trait PublicProperties
{
    public function __isset($offset)
    {
        return $this->containerOffsetExists($offset);
    }

    public function __get($offset)
    {
        return $this->containerGet($offset);
    }

    public function __set($offset, $value)
    {
        return $this->containerSet($offset, $value);
    }

    public function __unset($offset)
    {
        return $this->containerUnset($offset);
    } 
}