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
    
    public function __construct(array $input = [], Observable $subscriber) {
        $this->addSubscriber($subscriber);

        $input = array_map(function($item){
            return $this->wrapIfObservable($item);
        }, $input);

        parent::__construct($input);
    }

    public function offsetSet($offset, $value) {
        $this->unsetByKey($offset);
        $value = $this->wrapIfObservable($value);
        parent::offsetSet($offset, $value);
        $this->notify();
    }

    public function offsetUnset($offset) {
        $this->unsetByKey($offset);
        parent::offsetUnset($offset);
        $this->notify();
    }

    protected function unsetByKey($key)
    {
        $old = parent::offsetGet($key);

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
        return $this->getArrayCopy();
    }
}