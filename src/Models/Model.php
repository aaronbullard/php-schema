<?php

namespace PhpSchema\Models;

use ArrayObject, ReflectionClass;
use PhpSchema\Validator;
use PhpSchema\Traits\Observing;
use PhpSchema\Traits\ConvertsType;
use PhpSchema\Contracts\Arrayable;
use PhpSchema\Contracts\Observable;
use PhpSchema\ValidationException;

abstract class Model extends ArrayObject implements Arrayable, Observable
{
    use Observing, ConvertsType;

    protected function getAttribute($key)
    {
        return parent::offsetGet($key);
    }

    protected function setAttribute($key, $value): void
    {
        parent::offsetSet($key, $value);
    }

    public function offsetSet($offset, $value)
    {
        $offset = $offset ?? count($this);

        $this->stopObserving($offset);

        parent::offsetSet($offset, $value);

        $this->startObserving($offset);
        
        $this->notify();
    }

    public function offsetUnset($offset)
    {
        $this->stopObserving($offset);

        parent::offsetUnset($offset);

        $this->notify();
    }

    public function toArray(): array
    {
        return array_map(function($value){
            return $value instanceof Arrayable 
                        ? $value->toArray() 
                        : $value;
        }, $this->getArrayCopy());
    }
}