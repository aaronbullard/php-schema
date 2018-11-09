<?php

namespace PhpSchema\Tests\Entity;

use PhpSchema\Models\SchemaModel;
use PhpSchema\Traits\MethodAccess;

class Car extends SchemaModel
{
    use MethodAccess;
    
    protected static $schema = [
        '$ref' => 'file://' . __DIR__ . '/../Schemas/car.json'
    ];

    public function __construct($car_id, $make, $licensePlate)
    {
        $color = null;
        parent::__construct(compact('car_id', 'make', 'licensePlate', 'color'));
    }
}