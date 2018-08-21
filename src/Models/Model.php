<?php

namespace PhpSchema\Models;

use ArrayObject;
use PhpSchema\Contracts\Arrayable;
use PhpSchema\Contracts\Observable;
use PhpSchema\Observers\ObserverFactory;
use PhpSchema\ValidationException;

abstract class Model extends ArrayObject implements Arrayable, Observable
{
    protected $subscribers = [];

    public function __construct($input, $flags = 0)
    {
        parent::__construct($input, $flags);
        
        foreach($this as $offset => $value){
            $this->stopObserving($offset);
            $this->startObserving($offset);
        }
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

    protected function startObserving($key)
    {
        $value = parent::offsetGet($key);

        if(ObserverFactory::isObservable($value)){
            $value = ObserverFactory::create($value, $this);
        } else {
            // Some unknown object
            if(is_object($value)){
                throw ValidationException::ARRAYABLE();
            }
        }

        parent::offsetSet($key, $value);
    }

    protected function stopObserving($key)
    {
        $value = parent::offsetGet($key);

        if($value instanceof Observable){
            $value->removeSubscriber($this);
        }
    }

    public function addSubscriber(Observable $sub): Observable
    {
        $hash = spl_object_hash($sub);

        $this->subscribers[$hash] = $sub;

        return $this;
    }

    public function removeSubscriber(Observable $sub): Observable
    {
        $id = spl_object_hash($sub);

        unset($this->subscribers[$id]);

        return $this;
    }

    public function notify($payload = null): void
    {
        \array_walk($this->subscribers, function($sub) use ($payload){
            $sub->notify($payload);
        });
    }

    public function toArray(): array
    {
        return array_map(function($value){
            return $value instanceof Arrayable 
                        ? $value->toArray() 
                        : $value;
        }, $this->getArrayCopy());
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    public function toObject()
    {
        return json_decode($this->toJson());
    }

    public function __toString(): string
    {
        return $this->toJson();
    }
}