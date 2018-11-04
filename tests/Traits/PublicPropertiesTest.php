<?php

namespace PhpSchema\Tests\Traits;

use PhpSchema\Tests\TestCase;
use PhpSchema\Tests\Entity\Person;
use PhpSchema\Tests\Entity\Address;
use PhpSchema\ValidationException;

class PublicPropertiesTest extends TestCase
{
    /** @test */
    public function it_sets_and_gets_public_properties()
    {
        $person = new Person("Aaron", "Bullard");
        $person->age = 42;

        $this->assertEquals($person->age, 42);
    }

    /** @test */
    public function it_unsets_properties()
    {
        $person = new Person("Aaron", "Bullard");
        $person->age = 42;

        unset($person->age);

        $this->assertFalse(array_key_exists('age', $person->toArray()));
    }

    /** @test */
    public function isset_works_on_public_properties()
    {
        $person = new Person("Aaron", "Bullard");

        $this->assertFalse(isset($person->age));
        $person->age = 42;
        $this->assertTrue(isset($person->age));
    }

    /** @test */
    public function it_throws_exception_on_type_invalidation()
    {
        $person = new Person("Aaron", "Bullard");

        $this->expectException(
            ValidationException::class,
            "There are errors in the following properties: age"
        );

        $person->age = "42";
    }

    /** @test */
    public function it_disallows_additional_properties()
    {
        $address = new Address("123 Walker Rd", null, "Charleston", "SC", "29464");

        $this->expectException(ValidationException::class);

        $address->hemisphere = "northern";
    }

}