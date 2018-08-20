<?php

namespace PhpSchema\Observers;

use ArrayObject;
use PhpSchema\Traits\Observing;
use PhpSchema\Contracts\Observable;
use PhpSchema\Traits\ConvertsType;
use PhpSchema\Contracts\Arrayable;

class ArrayObserver extends ArrayObject implements Arrayable, Observable
{
    use Observing, ConvertsType;
    
    public function __construct(array $input = [], Observable $subscriber)
    {
        $this->addSubscriber($subscriber);

        parent::__construct($input);

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

    protected function startObserving($key): void
    {
        $value = parent::offsetGet($key);

        if(ObserverFactory::isObservable($value)){
            $value = ObserverFactory::create($value, $this);
        }

        parent::offsetSet($key, $value);
    }

    protected function stopObserving($key): void
    {
        $value = parent::offsetGet($key);

        if($value instanceof Observable){
            $value->removeSubscriber($this);
        }
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