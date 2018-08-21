<?php

namespace PhpSchema\Tests\Entity;

class Address extends PublicPropertiesModel
{
    protected static $schema = [
        '$ref' => 'file://' . __DIR__ . '/../Schemas/address.json'
    ];

    public function __construct($street_1, $street_2 = null, $city, $state, $zipcode)
    {
        parent::__construct(compact('street_1', 'street_2', 'city', 'state', 'zipcode'));
    }
}