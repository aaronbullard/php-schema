<?php

namespace PhpSchema\Observers;

use StdClass;
use PhpSchema\Contracts\Observable;

class ObserverFactory
{
    public static function create($value, Observable $subscriber)
    {
        if ($value instanceof StdClass) {
            $value = clone $value;
            return new StdClassObserver($value, $subscriber);
        }

        if (is_array($value)) {
            return new ArrayObserver($value, $subscriber);
        }

        if ($value instanceof Observable) {
            $value->addSubscriber($subscriber);
            return $value;
        }

        throw new \InvalidArgumentException("$value is not observable");
    }

    public static function isObservable($value): bool
    {
        return is_array($value) || $value instanceof StdClass || $value instanceof Observable;
    }
}
