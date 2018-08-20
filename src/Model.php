<?php

namespace PhpSchema;

use PhpSchema\Traits\Observing;
use PhpSchema\Contracts\Arrayable;
use PhpSchema\Contracts\Observable;

abstract class Model implements Arrayable, Observable
{
    use Observing;

    abstract protected function getAttribute($key);

    abstract protected function setAttribute($key, $value): void;

    abstract protected function unsetAttribute($key): void;

    abstract protected function keyExists($key);

    abstract public function toArray(): array;
}