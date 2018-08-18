<?php

namespace PhpSchema\Observers;

use StdClass;
use PhpSchema\Contracts\Arrayable;
use PhpSchema\Contracts\Verifiable;
use PhpSchema\ValidationException;

class ObjectObserver implements Arrayable
{
    protected $obj;

    protected $parent;

    public function __construct($obj, Verifiable $parent)
    {
        static::validateObject($obj);
        $this->parent = $parent;
        $this->obj = $obj;
    }

    public function __get($key)
    {
        return $this->obj->$key;
    }

    public function __set($key, $value)
    {
        $this->obj->$key = $value;

        $this->parent->validate();
    }

    public function __call($method, $args)
    {
        $value = $this->obj->$method(...$args);

        $this->parent->validate();

        return $value;
    }

    public function toArray(): array
    {
        if($this->obj instanceof Arrayable){
            return $this->obj->toArray();
        }

        return json_decode(json_encode($this->obj), true);
    }

    protected static function validateObject($obj)
    {
        if($obj instanceof StdClass || $obj instanceof Arrayable){
            return;
        }

        throw ValidationException::ARRAYABLE();
    }
}