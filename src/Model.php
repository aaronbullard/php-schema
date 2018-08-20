<?php

namespace PhpSchema;

use PhpSchema\Traits\Observing;
use PhpSchema\Contracts\Arrayable;
use PhpSchema\Contracts\Observable;
use PhpSchema\Observers\ObserverFactory;

abstract class Model implements Arrayable, Observable
{
    use Observing;

    abstract protected function getAttribute($key);

    abstract protected function setAttribute($key, $value): void;

    abstract protected function unsetAttribute($key): void;

    abstract protected function keyExists($key);

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