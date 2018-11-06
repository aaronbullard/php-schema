<?php

namespace PhpSchema\Traits;

/**
 * Must implement Iterator interface
 */
trait Loopable
{
    protected $position = 0;

    public function rewind()
    {
        $this->position = 0;
    }

    public function current()
    {
        return $this->containerGet($this->key());
    }

    public function key()
    {
        $keys = $this->containerKeys();

        return isset($keys[$this->position]) ? $keys[$this->position] : null;
    }

    public function next()
    {
        ++$this->position;
    }

    public function valid()
    {
        return $this->containerOffsetExists($this->key());
    }
}
