<?php

namespace PhpSchema\Observers;

use StdClass;
use PhpSchema\Traits\Observing;
use PhpSchema\Contracts\Observable;
use PhpSchema\Contracts\Arrayable;
use PhpSchema\Contracts\Verifiable;
use PhpSchema\ValidationException;

class StdClassObserver implements Arrayable, Observable
{
    use Observing;

    protected $obj;

    public function __construct(StdClass $obj, Observable $subscriber)
    {
        $this->obj = $obj;
        $this->addSubscriber($subscriber);

        foreach($this->obj as $prop => $value){
            $this->obj->$prop = $this->wrapIfObservable($value);
        }
    }

    public function __get($key)
    {
        return $this->obj->$key;
    }

    public function __set($key, $value)
    {
        $this->obj->$key = $this->wrapIfObservable($value);

        $this->notify();
    }

    protected function wrapIfObservable($value)
    {
        if(ObserverFactory::isObservable($value)){
            $value = ObserverFactory::create($value, $this);
        }

        return $value;
    }

    // public function __call($method, $args)
    // {
    //     $value = $this->obj->$method(...$args);

    //     $this->parent->validate();

    //     return $value;
    // }

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