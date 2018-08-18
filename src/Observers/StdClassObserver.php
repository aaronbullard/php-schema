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

        foreach($this->obj as $key => $value){
            $this->unsetByKey($key);
            $this->obj->$key = $this->wrapIfObservable($value);
        }
    }

    public function __get($key)
    {
        return $this->obj->$key;
    }

    public function __set($key, $value)
    {
        $this->unsetByKey($key);
        
        $this->obj->$key = $this->wrapIfObservable($value);

        $this->notify();
    }

    public function __unset($key)
    {
        $this->unsetByKey($key);

        unset($this->obj->$key);

        $this->notify();
    }

    protected function unsetByKey($key)
    {
        $old = $this->obj->$key;

        if($old instanceof Observable){
            $old->removeSubscriber($this);
        }
    }

    protected function wrapIfObservable($value)
    {
        if(ObserverFactory::isObservable($value)){
            $value = ObserverFactory::create($value, $this);
        }

        return $value;
    }

    public function toArray(): array
    {
        $arr = [];

        foreach($this->obj as $prop => $value){
            if($value instanceof Arrayable){
                $arr[$prop] = $value->toArray();
            } else {
                $arr[$prop] = $value;
            }
        }

        return json_decode(json_encode($arr), true);
    }
}