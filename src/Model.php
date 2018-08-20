<?php

namespace PhpSchema;

use PhpSchema\Contracts\Arrayable;
use PhpSchema\Contracts\Observable;
use PhpSchema\Observers\ObserverFactory;

abstract class Model implements Arrayable, Observable
{
    protected $subscribers = [];

    abstract protected function getAttribute($key);

    abstract protected function setAttribute($key, $value): void;

    abstract protected function unsetAttribute($key): void;

    abstract protected function keyExists($key);

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

    protected function startObserving($key)
    {
        $value = $this->getAttribute($key);

        if(ObserverFactory::isObservable($value)){
            $value = ObserverFactory::create($value, $this);
        }

        $this->setAttribute($key, $value);
    }

    protected function stopObserving($key)
    {
        $value = $this->getAttribute($key);

        if($value instanceof Observable){
            $value->removeSubscriber($this);
        }
    }

    abstract public function toArray(): array;
}