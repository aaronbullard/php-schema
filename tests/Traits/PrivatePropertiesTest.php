<?php

namespace PhpSchema\Tests\Traits;

use PhpSchema\Tests\TestCase;
use PhpSchema\Tests\Entity\Car;
use PhpSchema\ValidationException;

class PrivatePropertiesTest extends TestCase
{
   /** @test */
   public function it_prevents_get_access()
   {
       $car = new Car(42, "Jeep", "ABC123");

       $this->expectException(\Error::class);
       $this->expectExceptionMessage('Error: Cannot access protected property PhpSchema\Tests\Entity\Car::$make');
      
       $make = $car->make;
   }

   /** @test */
   public function it_prevents_set_access()
   {
       $car = new Car(42, "Jeep", "ABC123");

       $this->expectException(\Error::class);
       $this->expectExceptionMessage('Error: Cannot access protected property PhpSchema\Tests\Entity\Car::$make');
      
       $car->make = "BMW";
   }

   /** @test */
   public function it_prevents_unset()
   {
       $car = new Car(42, "Jeep", "ABC123");

       $this->expectException(\Error::class);
       $this->expectExceptionMessage('Error: Cannot access protected property PhpSchema\Tests\Entity\Car::$make');
      
       unset($car->make);
   }

   /** @test */
   public function it_prevents_array_access()
   {
       $car = new Car(42, "Jeep", "ABC123");

       $this->expectException(\Error::class);
       $this->expectExceptionMessage('Error: Cannot use object of type PhpSchema\Tests\Entity\Car as array');
      
       $car[] = "BMW";
   }
   
}