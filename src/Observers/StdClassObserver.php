<?php

namespace PhpSchema\Observers;

use ArrayObject, StdClass;
use PhpSchema\Contracts\Observable;

class StdClassObserver extends Observer
{
    public function __construct(StdClass $obj, Observable $subscriber)
    {
        $this->addSubscriber($subscriber);

        parent::__construct($obj, ArrayObject::ARRAY_AS_PROPS);

        foreach($this as $offset => $value){
            $this->stopObserving($offset);
            $this->startObserving($offset);
        }
    }
}