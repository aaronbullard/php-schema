<?php

namespace PhpSchema\Tests\Traits;

use PhpSchema\Tests\TestCase;
use PhpSchema\Tests\Entity\Car;
use PhpSchema\Tests\Entity\Vehicle;
use PhpSchema\ValidationException;

class MethodAccessTest extends TestCase
{
    /** @test */
    public function it_creates_a_getter()
    {
        $car = new Car(42, "Jeep", "ABC123");

        $this->assertEquals($car->car_id(), 42);
        $this->assertEquals($car->make(), "Jeep");
        $this->assertEquals($car->licensePlate(), "ABC123");
    }

    /** @test */
    public function it_creates_a_setter()
    {
        $car = new Car(42, "Jeep", "ABC123");
        $car->make("BMW");

        $this->assertEquals($car->make(), "BMW");
    }

    /** @test */
    public function setter_is_chainable()
    {
        $car = new Car(42, "Jeep", "ABC123");
        $car->make("BMW")
            ->licensPlate("DEF456");

        $this->assertEquals($car->make(), "BMW");
        $this->assertEquals($car->licensPlate(), "DEF456");
    }

    /** @test */
    public function setter_is_validated()
    {
        $car = new Car(42, "Jeep", "ABC123");

        $this->expectException(ValidationException::class);
        $car->car_id("42");
    }

    /** @test */
    public function getter_can_be_modified()
    {
        $car = new Vehicle(42, "Jeep", "ABC123");

        $this->assertEquals($car->getCar_id(), 42);
        $this->assertEquals($car->getMake(), "Jeep");
        $this->assertEquals($car->getLicensePlate(), "ABC123");
    }

    /** @test */
    public function setter_can_be_modified()
    {
        $car = new Vehicle(42, "Jeep", "ABC123");
        $car->setMake("BMW");

        $this->assertEquals($car->getMake(), "BMW");
    }

    /** @test */
    public function it_throws_exception_for_bad_method_called()
    {
        $car = new Vehicle(42, "Jeep", "ABC123");

        $this->expectException(\BadMethodCallException::class);

        $car->year();
    }
}
