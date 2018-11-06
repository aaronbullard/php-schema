<?php

/**
 * Must implement ArrayAccess interface
 */
namespace PhpSchema\Traits;

trait ArrayAccessible
{
    public function offsetExists($offset): bool
    {
        return $this->containerOffsetExists($offset);
    }

    public function offsetGet($offset)
    {
        return $this->containerGet($offset);
    }

    public function offsetSet($offset, $value)
    {
        $offset = $offset ?? count($this->container);

        $this->containerSet($offset, $value);
    }

    public function offsetUnset($offset)
    {
        $this->containerUnset($offset);
    }
}