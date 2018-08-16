<?php

namespace PhpSchema\Tests\Entity;

use PhpSchema\Model;

class Person extends PublicPropertiesModel
{
    protected static $schema = [
        '$ref' => 'file://' . __DIR__ . '/../Schemas/person.json'
    ];

    public function __construct($firstName, $lastName)
    {
        parent::__construct($firstName, $lastName);
    }
}