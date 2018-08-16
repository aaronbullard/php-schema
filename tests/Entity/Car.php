<?php

namespace PhpSchema\Tests\Entity;

use PhpSchema\Model;
use PhpSchema\Traits\MethodAccess;

class Car extends Model
{
    use MethodAccess;
    
    protected static $schema = [
        '$ref' => 'file://' . __DIR__ . '/../Schemas/car.json'
    ];

    public function __construct($car_id, $make, $licensePlate)
    {
        parent::__construct($car_id, $make, $licensePlate);
    }
}