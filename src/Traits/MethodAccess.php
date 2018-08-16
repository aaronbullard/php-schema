<?php

namespace PhpSchema\Traits;

use BadMethodCallException;

trait MethodAccess
{
    public function __call($method, $args)
    {
        if($this->isGetter($method) && empty($args)){
            $key = $this->getMethodToAttributeTransformer($method);
            return $this->_attributes[$key];
        } 
        
        if($this->isSetter($method) && count($args)) {
            $key = $this->setMethodToAttributeTransformer($method);
            $this->set($key, $args[0]);
            $this->validate();
            return $this;
        }

        throw new BadMethodCallException("Unrecognized method '". get_class($this) . "::$method'.");
    }

    protected function isGetter(string $method): bool
    {
        return true;
    }

    protected function isSetter(string $method): bool
    {
        return true;
    }

    protected function getMethodToAttributeTransformer(string $method): string
    {
        return $method;
    }

    protected function setMethodToAttributeTransformer(string $method): string
    {
        return $method;
    }
}