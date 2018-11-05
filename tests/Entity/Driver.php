<?php

namespace PhpSchema\Tests\Entity;

use PhpSchema\Models\SchemaModel;
use PhpSchema\Traits\PublicProperties;

class Driver extends SchemaModel
{
    use PublicProperties;
    
    protected static $schema = [
        '$ref' => 'file://' . __DIR__ . '/../Schemas/driver.json'
    ];

    public function __construct(Person $person)
    {
        parent::__construct(compact('person'));
    }
}