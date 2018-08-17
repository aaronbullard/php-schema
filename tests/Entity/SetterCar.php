<?php

namespace PhpSchema\Tests\Entity;

use PhpSchema\Model;
use PhpSchema\Traits\MethodAccessSetterGetter;

class SetterCar extends Model
{
    use MethodAccessSetterGetter;
    
    protected static $schema = [
        '$ref' => 'file://' . __DIR__ . '/../Schemas/car.json'
    ];

    public function __construct($car_id, $make, $licensePlate)
    {
        parent::__construct($car_id, $make, $licensePlate);
    }
}