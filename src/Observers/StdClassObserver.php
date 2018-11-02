<?php

namespace PhpSchema\Observers;

use StdClass;
use PhpSchema\Contracts\Observable;
use PhpSchema\Traits\PrivateProperties;

class StdClassObserver extends Observer
{
    // use PrivateProperties;

    public function __construct(StdClass $obj, Observable $subscriber)
    {
        parent::__construct($obj, $subscriber);
        $this->setFlags(self::ARRAY_AS_PROPS);
    }
}