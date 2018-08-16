<?php

namespace PhpSchema\Traits;

use ArgumentCountError;
use BadMethodCallException;

trait MethodAccess
{
    public function __call($method, $args)
    {
        if($this->isGetterMethod($method) && empty($args)){
            $key = $this->getMethodToAttributeTransformer($method);
            return $this->_attributes[$key];
        } 
        
        if($this->isSetterMethod($method)) {
            // No arguments passed to setter
            if(count($args) === 0){
                throw new ArgumentCountError("Too few arguments to function ".
                    __CLASS__."::$method(), ".count($args).
                    " passed in and exactly 1 expected");
            }

            $key = $this->setMethodToAttributeTransformer($method);
            $this->set($key, $args[0]);
            $this->validate();

            return $this;
        }

        throw new BadMethodCallException("Unrecognized method ". __CLASS__ . "::$method()");
    }

    protected function isGetterMethod(string $method): bool
    {
        return true;
    }

    protected function isSetterMethod(string $method): bool
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