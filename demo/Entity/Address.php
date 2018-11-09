<?php

namespace PhpSchema\Demo\Entity;

use PhpSchema\Models\SchemaModel;
use PhpSchema\Traits\PublicProperties;

class Address extends SchemaModel
{
    use PublicProperties;

    protected static $schema = [
        '$ref' => 'file://' . __DIR__ . '/../Schemas/address.json'
    ];

    public function __construct(string $street_1, string $street_2 = null, string $city, string $state, string $zipcode)
    {
        parent::__construct(compact('street_1', 'street_2', 'city', 'state', 'zipcode'));
    }
}