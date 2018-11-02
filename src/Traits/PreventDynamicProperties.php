<?php

namespace PhpSchema\Traits;

trait PreventDynamicProperties
{
    public function __set($key, $value)
    {
        trigger_error("Attempt to assign property '{$key}' of non-object", E_USER_WARNING);
    }

    public function __get($key)
    {
        trigger_error("Trying to get property '{$key}' of non-object", E_USER_WARNING);
    }
}