<?php

namespace PhpSchema\Demo\Entity;

use PhpSchema\Models\SchemaModel;
use PhpSchema\Traits\MethodAccess;

class Person extends SchemaModel
{
    use MethodAccess;

    protected static $schema = [
        '$ref' => 'file://' . __DIR__ . '/../Schemas/person.json'
    ];

    public function __construct(string $firstName, string $lastName)
    {
        parent::__construct(compact('firstName', 'lastName'));
    }

    public function fullName()
    {
        return $this->firstName() . " " . $this->lastName();
    }
}