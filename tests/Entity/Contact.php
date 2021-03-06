<?php

namespace PhpSchema\Tests\Entity;

use PhpSchema\Models\SchemaModel;
use PhpSchema\Traits\PublicProperties;

class Contact extends SchemaModel
{
    use PublicProperties;
    
    protected static $schema = [
        '$ref' => 'file://' . __DIR__ . '/../Schemas/contact.json'
    ];

    public function __construct($person)
    {
        parent::__construct(compact('person'));
    }
}