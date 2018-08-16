<?php

namespace PhpSchema\Tests\Entity;

use PhpSchema\Model;
use PhpSchema\Traits\MethodAccess;

class Vehicle extends Model
{
    use MethodAccess;
    
    protected static $schema = [
        '$ref' => 'file://' . __DIR__ . '/../Schemas/car.json'
    ];

    public function __construct($car_id, $make, $licensePlate)
    {
        parent::__construct($car_id, $make, $licensePlate);
    }

    protected function isGetter(string $method): bool
    {
        return substr($method, 0, 3) === 'get';
    }

    protected function isSetter(string $method): bool
    {
        return substr($method, 0, 3) === 'set';
    }

    protected function getMethodToAttributeTransformer(string $method): string
    {
        return lcfirst(substr($method, 3));
    }

    protected function setMethodToAttributeTransformer(string $method): string
    {
        return lcfirst(substr($method, 3));
    }
}