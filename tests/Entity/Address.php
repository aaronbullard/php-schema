<?php

namespace PhpSchema\Tests\Entity;

use PhpSchema\Model;

class Address extends Model
{
    protected $schema = [
        '$ref' => 'file://' . __DIR__ . '/../Schemas/address.json'
    ];

    public function __construct($street_1, $street_2 = null, $city, $state, $zipcode)
    {
        parent::__construct($street_1, $street_2 = null, $city, $state, $zipcode);
    }
}