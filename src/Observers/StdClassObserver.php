<?php

namespace PhpSchema\Observers;

use ArrayObject, StdClass;
use PhpSchema\Contracts\Observable;

class StdClassObserver extends Observer
{
    public function __construct(StdClass $obj, Observable $subscriber)
    {
        $this->setFlags(ArrayObject::ARRAY_AS_PROPS);

        parent::__construct($obj, $subscriber);
    }
}