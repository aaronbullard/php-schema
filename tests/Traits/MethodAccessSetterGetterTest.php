<?php

namespace PhpSchema\Tests\Traits;

use PhpSchema\Tests\TestCase;
use PhpSchema\Tests\Entity\SetterCar;
use PhpSchema\ValidationException;

class MethodAccessSetterGetterTest extends TestCase
{
    /** @test */
    public function it_creates_a_getter()
    {
        $car = new SetterCar(42, "Jeep", "ABC123");

        $this->assertEquals($car->getCar_id(), 42);
        $this->assertEquals($car->getMake(), "Jeep");
        $this->assertEquals($car->getLicensePlate(), "ABC123");
    }

    /** @test */
    public function it_creates_a_setter()
    {
        $car = new SetterCar(42, "Jeep", "ABC123");
        $car->setMake("BMW");

        $this->assertEquals($car->getMake(), "BMW");
    }
}