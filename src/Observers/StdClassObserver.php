<?php

namespace PhpSchema\Observers;

use StdClass, Iterator;
use PhpSchema\Model;
use PhpSchema\Traits\Iterates;
use PhpSchema\Traits\ConvertsType;
use PhpSchema\Contracts\Arrayable;
use PhpSchema\Contracts\Observable;
use PhpSchema\Traits\PublicProperties;
use PhpSchema\ValidationException;

use PhpSchema\Traits\Observing;

class StdClassObserver extends Model implements Iterator, Arrayable
{
    use PublicProperties, Iterates, ConvertsType;

    protected $obj;

    public function __construct(StdClass $obj, Observable $subscriber)
    {
        // Do we need to clone this?
        $this->obj = clone $obj;
        $this->addSubscriber($subscriber);

        // Setup subscription
        foreach($this->obj as $key => $value){
            $this->stopObserving($key);
            $this->startObserving($key);
        }
    }

    protected function getAttribute($key)
    {
        return $this->obj->$key;
    }

    protected function setAttribute($key, $value): void
    {
        $this->obj->$key = $value;
    }

    protected function unsetAttribute($key): void
    {
        unset($this->obj->$key);
    }

    protected function keyExists($key)
    {
        return isset($this->obj->$key);
    }

    public function toArray(): array
    {
        $arr = [];

        foreach($this->obj as $prop => $value){
            $arr[$prop] = $value instanceof Arrayable 
                            ? $value->toArray() 
                            : $value;
        }

        return $arr;
    }
}