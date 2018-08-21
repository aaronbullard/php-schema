<?php

namespace PhpSchema\Traits;

use PhpSchema\ValidationException;
use PhpSchema\Contracts\Observable;
use PhpSchema\Observers\ObserverFactory;

trait Observing
{
    protected $subscribers = [];

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
        } else {
            // Some unknown object
            if(is_object($value)){
                throw ValidationException::ARRAYABLE();
            }
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
}