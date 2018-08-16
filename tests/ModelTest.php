<?php

namespace PhpSchema\Tests;

use PhpSchema\Tests\Entity\Contact;
use PhpSchema\Tests\Entity\Person;
use PhpSchema\Tests\Entity\Address;
use PhpSchema\Tests\Entity\PhoneNumber;
use PhpSchema\Tests\Entity\UnknownClass;
use PhpSchema\ValidationException;

class ModelTest extends TestCase
{
    /** @test */
    public function it_sets_init_properties()
    {
        $person = new Person("Aaron", "Bullard");

        $this->assertEquals($person->firstName, "Aaron");
        $this->assertEquals($person->lastName, "Bullard");
    }

    /** @test */
    public function it_requires_required_properties_at_construction()
    {
        $this->expectException(
            ValidationException::class
        );

        $this->expectExceptionMessage(
            "There are errors in the following properties: lastName"
        );
        
        $person = new Person("Aaron", null);
    }

    /** @test */
    public function it_converts_to_an_array()
    {
        $person = new Person("Aaron", "Bullard");

        $arr = $person->toArray();

        $this->assertEquals($arr['firstName'], "Aaron");
    }

    /** @test */
    public function it_converts_to_a_stdClass()
    {
        $person = new Person("Aaron", "Bullard");

        $stdClass = $person->toObject();

        $this->assertInstanceOf(\StdClass::class, $stdClass);
        $this->assertEquals($stdClass->firstName, "Aaron");
    }

    /** @test */
    public function it_validates_nested_objects()
    {
        $person = new Person("Aaron", "Bullard");
        $address = new Address("123 Walker Rd", null, "Charleston", "SC", "29464");

        $person->address = $address;

        $this->assertInstanceOf(Address::class, $person->address);
    }

    /** @test */
    public function it_validates_nested_objects_that_are_not_extending_model()
    {
        $person = new Person("Aaron", "Bullard");
        $address = new Address("123 Walker Rd", null, "Charleston", "SC", "29464");
        $address = $address->toObject();
        $person->address = $address;

        unset($address->state);

        $this->expectException(
            ValidationException::class
        );

        $this->expectExceptionMessage(
            "There are errors in the following properties: address.state"
        );

        $person->address = $address;
    }

    /** @test */
    public function it_validates_arrayable_objects()
    {
        $person = new Person("Aaron", "Bullard");
        $phoneNumber = new PhoneNumber("843-867-5309");

        $person->phoneNumber = $phoneNumber;

        $p = $person->toArray();

        $this->assertEquals($p['phoneNumber']['number'], $phoneNumber->number());
    }

    /** @test */
    public function it_accepts_stdClasses()
    {
        $person = new Person("Aaron", "Bullard");
        $phoneNumber = new \StdClass;
        $phoneNumber->number = "843-867-5309";

        $person->phoneNumber = $phoneNumber;

        $p = $person->toArray();

        $this->assertEquals($p['phoneNumber']['number'], $phoneNumber->number);
    }

    /** @test */
    public function it_ignores_unknown_classes()
    {
        $person = new Person("Aaron", "Bullard");
        
        $random = new UnknownClass();

        $person->random = $random;

        // converts unknown class to null
        $this->assertEquals($person->toArray()['random'], null);

        // enforces schema
        $this->expectException(
            ValidationException::class
        );
        $person->phoneNumber = $random;
    }

    /** @test */
    public function it_validates_arrays_of_models()
    {
        $person = new Person("Aaron", "Bullard");
        $contact = new Contact($person);

        $contact->phoneNumbers = [];
        $contact->phoneNumbers[] = new PhoneNumber("1");
        $contact->phoneNumbers[] = new PhoneNumber("2");

        $this->assertCount(2, $contact->phoneNumbers);
        $this->expectException(
            ValidationException::class
        );
        $this->expectExceptionMessage(
            "There are errors in the following properties: phoneNumbers[2].number"
        );
        $contact->phoneNumbers[] = new Person("Bob", "Smith");
    }

    /** @test */
    public function array_attributes_are_iterable()
    {
        $person = new Person("Aaron", "Bullard");
        $contact = new Contact($person);

        $contact->phoneNumbers = [];
        $contact->phoneNumbers[] = new PhoneNumber("1");
        $contact->phoneNumbers[] = new PhoneNumber("2");

        $this->assertCount(2, $contact->phoneNumbers);
    }

}