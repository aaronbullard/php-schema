<?php

namespace PhpSchema\Traits;

use ArgumentCountError;
use BadMethodCallException;

trait MethodAccessSetterGetter
{
    use MethodAccess;
   
    protected function isGetterMethod(string $method): bool
    {
        return substr($method, 0, 3) === 'get';
    }

    protected function isSetterMethod(string $method): bool
    {
        return substr($method, 0, 3) === 'set';
    }

    protected function getMethodToAttributeTransformer(string $method): string
    {
        return lcfirst(substr($method, 3));
    }

    protected function setMethodToAttributeTransformer(string $method): string
    {
        return lcfirst(substr($method, 3));
    }
}