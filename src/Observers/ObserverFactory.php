<?php

namespace PhpSchema\Observers;

use StdClass;
use PhpSchema\Contracts\Verifiable;

class ObserverFactory
{
    public static function createIfObservable($value, Verifiable $validator)
    {
        if($value instanceof StdClass){
            return new ObjectObserver($value, $validator);
        }

        if(is_array($value)){
            return new ArrayObserver($value, $validator);
        }
 
        return $value;
    }

    public static function isObservable($value): bool
    {
        return is_array($value) || $value instanceof StdClass;
    }
}