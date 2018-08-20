<?php

namespace PhpSchema\Observers;

use StdClass, Iterator;
use PhpSchema\Traits\Iterates;
use PhpSchema\Traits\Observing;
use PhpSchema\Traits\ConvertsType;
use PhpSchema\Contracts\Arrayable;
use PhpSchema\Contracts\Verifiable;
use PhpSchema\Contracts\Observable;
use PhpSchema\ValidationException;

class StdClassObserver implements Arrayable, Observable, Iterator
{
    use Observing, Iterates, ConvertsType;

    protected $obj;

    public function __construct(StdClass $obj, Observable $subscriber)
    {
        $this->obj = $obj;
        $this->addSubscriber($subscriber);

        // Setup subscription
        foreach($this->obj as $key => $value){
            $this->stopObserving($key);
            $this->startObserving($key);
        }
    }

    public function __get($key)
    {
        return $this->obj->$key;
    }

    public function __set($key, $value)
    {
        $this->stopObserving($key);
        
        $this->obj->$key = $value;
        
        $this->startObserving($key);

        $this->notify();
    }

    public function __unset($key)
    {
        $this->stopObserving($key);

        unset($this->obj->$key);

        $this->notify();
    }

    protected function startObserving($key)
    {
        $value = $this->obj->$key;

        if(ObserverFactory::isObservable($value)){
            $value = ObserverFactory::create($value, $this);
        }

        $this->obj->$key = $value;
    }

    protected function stopObserving($key)
    {
        $value = $this->obj->$key;

        if($value instanceof Observable){
            $value->removeSubscriber($this);
        }
    }
    
    public function toArray(): array
    {
        $arr = [];

        foreach($this->obj as $prop => $value){
            $arr[$prop] = $value instanceof Arrayable ? $value->toArray() : $value;
        }

        return $arr;
    }
}