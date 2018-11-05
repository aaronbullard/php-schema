<?php

namespace PhpSchema\Tests\Models;

use PhpSchema\Tests\TestCase;
use PhpSchema\Factory;
use PhpSchema\Tests\Entity\Car;
use PhpSchema\Tests\Entity\Person;
use PhpSchema\Tests\Entity\Driver;
use PhpSchema\Tests\Entity\Contact;
use PhpSchema\Tests\Entity\Address;
use PhpSchema\Tests\Entity\PhoneNumber;
use PhpSchema\Contracts\Observable;
use PhpSchema\ValidationException;

class SchemaModelTest extends TestCase
{
    /** @test */
    public function it_sets_and_gets_init_properties()
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
    public function it_prevents_array_access()
    {
        $person = new Person("Aaron", "Bullard");

        $this->expectException(\Throwable::class);
        $person['firstName'];
    }

    /** @test */
    public function it_is_not_iterable()
    {
        $person = new Person("Aaron", "Bullard");

        $this->assertNotInstanceOf(\Traversable::class, $person);
        $this->assertNotInstanceOf(\Iterator::class, $person);
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
        $address = new Address("123 Walker Rd", null, "Charleston", "SC", "29464");
        $person->address = $address->toObject();

        $stdClass = $person->toObject();

        $this->assertInstanceOf(\StdClass::class, $stdClass);
        $this->assertEquals($stdClass->firstName, "Aaron");
        $this->assertEquals($stdClass->address->city, "Charleston");
    }

    /** @test */
    public function it_converts_to_an_array_deep_structures()
    {
        $person = new Person("Aaron", "Bullard");
        $person->phoneNumber = (object)[
            'name' => '1',
            'children' => [
                ['name' => '1.1'],
                ['name' => '1.2'],
                [
                    'name' => '1.3',
                    'child' => (object)['name' => '1.3.1']
                ]
            ]
        ];

        $arr = $person->toArray();
        $this->assertEquals($arr['firstName'], "Aaron");
        $this->assertEquals($arr['phoneNumber']['name'], "1");
        $this->assertEquals($arr['phoneNumber']['children'][0]['name'], "1.1");
        $this->assertEquals($arr['phoneNumber']['children'][1]['name'], "1.2");
        $this->assertEquals($arr['phoneNumber']['children'][2]['name'], "1.3");
        $this->assertEquals($arr['phoneNumber']['children'][2]['child']['name'], "1.3.1");
    }

    /** @test */
    public function schemamodel_child_notifies_parent_of_changes()
    {
        $person = new Person("Aaron", "Bullard");
        $driver = new Driver($person);

        // Works because each extends Model which is an observable
        $this->expectException(ValidationException::class);
        $driver->person->age = 12; // less than requirement of 16
    }

    /** @test */
    public function it_validates_nested_objects_that_are_not_extending_model()
    {
        $person = new Person("Aaron", "Bullard");
        $address = new Address("123 Walker Rd", null, "Charleston", "SC", "29464");
        
        // Address is now a StdClass object
        $address = $address->toObject();
        $person->address = $address;
    
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            "There are errors in the following properties: address.state"
        );

        // Create an invalid state
        unset($address->state);
        $person->address = $address;
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
    public function it_enforces_arrayable_interface()
    {
        $person = new Person("Aaron", "Bullard");

         // enforces schema
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            "Embeddable classes must implement the PhpSchema\Contracts\Arrayable interface"
        );

        $number = new class('202-867-5309') {
            public $number;

            function __construct($num){
                $this->number = $num;
            }
        };

        $person->phoneNumber = $number;
    }

    /** @test */
    public function it_validates_arrays_of_models()
    {
        // $this->markTestSkipped();
        $person = new Person("Aaron", "Bullard");
        $contact = new Contact($person);

        $contact->phoneNumbers = [];
        $contact->phoneNumbers[] = new PhoneNumber("1");
        $contact->phoneNumbers[] = new PhoneNumber("2");

        $this->assertCount(2, $contact->phoneNumbers);
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            "There are errors in the following properties: phoneNumbers[2].number"
        );
        $contact->phoneNumbers[] = new Person("Bob", "Smith");
    }

    /** @test */
    public function it_prevents_embeddable_stdclass_objects_from_mutating_by_reference()
    {
        $person = new Person("Aaron", "Bullard");
        $address = (object) [
            'street_1' => '123 Walker Dr',
            'city' => 'Charleston',
            'state' => 'SC',
            'zipcode' => '29492'
        ];

        // set
        $person->address = $address;

        // mutate outside the object
        $address->state = 'NJ';

        $this->assertEquals($person->address->state, "SC");
    }

    /** @test */
    public function it_validates_embeddables_when_they_mutate()
    {
        $person = new Person("Aaron", "Bullard");
        $person->address = (object) [
            'street_1' => '123 Walker Dr',
            'city' => 'Charleston',
            'state' => 'SC',
            'zipcode' => '29492'
        ];

        $this->expectException(ValidationException::class);
        $person->address->state = 'South Carolina';
    }

    /** @test */
    public function it_stops_observing_orphaned_objects()
    {
        $person = new Person("Aaron", "Bullard");
        $person->address = (object) [
            'street_1' => '123 Walker Dr',
            'city' => 'Charleston',
            'state' => 'SC',
            'zipcode' => '29492'
        ];

        $address = $person->address;
        $this->assertInstanceOf(Observable::class, $address);

        unset($person->address);
        $this->assertFalse(isset($person->address));

        // Ensure no validation error is thrown, no longer observing
        $this->assertInstanceOf(Observable::class, $address);
        $address->state = "South Carolina";
    }

    public function xtest_speed_test()
    {
        $times = 10000;
        $start = microtime(true);
        $dto = 0;
        $model = 0;

        for($i=0; $i < $times; $i++){
            $obj = (object) [
                "firstName" => "Aaron",
                "lastName" => "Bullard"
            ];
            $obj->firstName = 'a';
            $obj->firstName = 'b';
            unset($obj);
        }
        $dto = microtime(true);

        for($i=0; $i < $times; $i++){
            $obj = new Person("Aaron", "Bullard");
            $obj->firstName = 'a';
            $obj->firstName = 'b';
            unset($obj);
        }
        $model = microtime(true);

        $dtoDiff = $dto - $start;
        $modelDiff = $model - $dto;

        print_r(compact('dtoDiff', 'modelDiff'));die;
    }

}