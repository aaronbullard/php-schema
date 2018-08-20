<?php

namespace PhpSchema\Traits;

trait PublicProperties
{
    public function __set($key, $value)
    {
        $this->stopObserving($key);

        $this->setAttribute($key, $value);

        $this->startObserving($key);

        $this->notify();
    }

    public function __get($key)
    {
        return $this->getAttribute($key);
    }

    public function __unset($key)
    {
        $this->stopObserving($key);

        $this->unsetAttribute($key);

        $this->notify();
    }

    public function __isset($key)
    {
        return $this->keyExists($key);
    }
}