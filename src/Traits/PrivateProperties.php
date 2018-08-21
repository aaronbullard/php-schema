<?php

namespace PhpSchema\Traits;

use Error;

trait PrivateProperties
{
    public function offsetExists($offset)
    {
        return false;
    }

    public function offsetGet($offset)
    {
        if(is_null($offset)){
            throw new Error("Error: Cannot use object of type ".__CLASS__." as array");
        }

        throw new Error("Error: Cannot access protected property ".__CLASS__."::$".$offset);
    }

    public function offsetSet($offset, $value)
    {
        if(is_null($offset)){
            throw new Error("Error: Cannot use object of type ".__CLASS__." as array");
        }

        throw new Error("Error: Cannot access protected property ".__CLASS__."::$".$offset);
    }

    public function offsetUnset($offset)
    {
        if(is_null($offset)){
            throw new Error("Error: Cannot use object of type ".__CLASS__." as array");
        }
        
        throw new Error("Error: Cannot access protected property ".__CLASS__."::$".$offset);
    }
}