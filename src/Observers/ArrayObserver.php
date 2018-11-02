<?php

namespace PhpSchema\Observers;

use PhpSchema\Contracts\Observable;

class ArrayObserver extends Observer
{
    public function __construct($input, Observable $subscriber)
    {
        parent::__construct($input, $subscriber);
    }
}