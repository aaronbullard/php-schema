<?php

namespace PhpSchema\Tests\Entity;

class Person extends PublicPropertiesModel
{
    protected static $schema = [
        '$ref' => 'file://' . __DIR__ . '/../Schemas/person.json'
    ];

    public function __construct($firstName, $lastName)
    {
        parent::__construct(compact('firstName', 'lastName'));
    }
}