<?php

namespace PhpSchema\Tests\Entity;

use PhpSchema\Contracts\Arrayable;

class PhoneNumber implements Arrayable
{
    protected $number;

    public function __construct($number)
    {
        $this->number = $number;
    }

    public function number()
    {
        return $this->number;
    }

    public function toArray(): array
    {
        return ['number' => $this->number];
    }
}