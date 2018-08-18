<?php

namespace PhpSchema\Observers;

use ArrayObject;
use PhpSchema\Traits\Observing;
use PhpSchema\Contracts\Observable;
use PhpSchema\Contracts\Arrayable;
use PhpSchema\Contracts\Verifiable;

class ArrayObserver extends ArrayObject implements Arrayable, Observable
{
    use Observing;
    
    public function __construct(array $input = [], Observable $subscriber) {
        $this->addSubscriber($subscriber);
        parent::__construct($input);
    }

    public function offsetSet($offset, $value) {
        parent::offsetSet($offset, $value);
        $this->notify();
    }

    public function offsetUnset($offset) {
        parent::offsetUnset($offset);
        $this->notify();
    }

    public function toArray(): array
    {
        return $this->getArrayCopy();
    }
}