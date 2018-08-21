<?php

namespace PhpSchema\Observers;

use ArrayObject;
use PhpSchema\Contracts\Observable;

class ArrayObserver extends Observer
{
    public function __construct(array $input = [], Observable $subscriber)
    {
        $this->addSubscriber($subscriber);

        parent::__construct($input);

        foreach($this as $offset => $value){
            $this->stopObserving($offset);
            $this->startObserving($offset);
        }
    }

}