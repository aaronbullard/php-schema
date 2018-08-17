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

    /** @test */
    public function setter_is_chainable()
    {
        $car = new SetterCar(42, "Jeep", "ABC123");
        $car->setMake("BMW")
            ->setLicensPlate("DEF456");

        $this->assertEquals($car->getMake(), "BMW");
        $this->assertEquals($car->getLicensPlate(), "DEF456");
    }

    /** @test */
    public function setter_is_validated()
    {
        $car = new SetterCar(42, "Jeep", "ABC123");

        $this->expectException(ValidationException::class);
        $car->setCar_id("42");
    }

    /** @test */
    public function it_throws_exception_for_bad_method_called()
    {
        $car = new SetterCar(42, "Jeep", "ABC123");

        $this->expectException(\BadMethodCallException::class);

        $year = $car->year();
    }

    /** @test */
    public function it_throws_exception_when_no_arguments_are_passed_to_a_setter()
    {
        $car = new SetterCar(42, "Jeep", "ABC123");

        $this->expectException(\ArgumentCountError::class);
        $this->expectExceptionMessage("Too few arguments to function PhpSchema\Tests\Entity\SetterCar::setMake(), 0 passed in and exactly 1 expected");

        $car->setMake();
    }
}