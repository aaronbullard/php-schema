<?php

namespace PhpSchema\Tests\Entity;

use PhpSchema\Models\SchemaModel;
use PhpSchema\Traits\PublicProperties;

class Address extends SchemaModel
{
    use PublicProperties;

    protected static $schema = [
        '$ref' => 'file://' . __DIR__ . '/../Schemas/address.json'
    ];

    public function __construct($street_1, $street_2 = null, $city, $state, $zipcode)
    {
        parent::__construct(compact('street_1', 'street_2', 'city', 'state', 'zipcode'));
    }
}