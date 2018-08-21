<?php

namespace PhpSchema\Tests\Entity;

use PhpSchema\Models\SchemaModel;
use PhpSchema\Traits\MethodAccess;
use PhpSchema\Traits\PrivateProperties;

class Car extends SchemaModel
{
    use MethodAccess, PrivateProperties;
    
    protected static $schema = [
        '$ref' => 'file://' . __DIR__ . '/../Schemas/car.json'
    ];

    public function __construct($car_id, $make, $licensePlate)
    {
        parent::__construct(compact('car_id', 'make', 'licensePlate'));
    }
}