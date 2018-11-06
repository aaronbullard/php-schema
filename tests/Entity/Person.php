<?php

namespace PhpSchema\Tests\Entity;

use PhpSchema\Models\SchemaModel;
use PhpSchema\Traits\PublicProperties;

class Person extends SchemaModel
{
    use PublicProperties;
    
    protected static $schema = [
        '$ref' => 'file://' . __DIR__ . '/../Schemas/person.json'
    ];

    public function __construct($firstName, $lastName)
    {
        parent::__construct(compact('firstName', 'lastName'));
    }

    public function changeFirstName($name)
    {
        $this->firstName = $name;
        
        return $this;
    }
}