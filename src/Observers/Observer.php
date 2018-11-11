<?php

namespace PhpSchema\Observers;

use PhpSchema\Models\Model;
use PhpSchema\Contracts\Observable;

abstract class Observer extends Model
{
    public function __construct($input = [], Observable $subscriber)
    {
        $this->addSubscriber($subscriber);
        parent::__construct($input);
    }
}
