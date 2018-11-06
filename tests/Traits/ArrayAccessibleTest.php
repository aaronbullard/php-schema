<?php

namespace PhpSchema\Tests\Traits;

use ArrayAccess;
use PhpSchema\Tests\TestCase;
use PhpSchema\Models\Model;
use PhpSchema\Traits\ArrayAccessible;

class Human extends Model implements ArrayAccess
{
    use ArrayAccessible;

    protected static $schema = [
        '$ref' => 'file://' . __DIR__ . '/../Schemas/person.json'
    ];

    public function __construct($firstName, $lastName)
    {
        parent::__construct(compact('firstName', 'lastName'));
    }
}

class ArrayAccessibleTest extends TestCase
{
    /** @test */
    public function it_sets_and_gets_public_properties()
    {
        $person = new Human("Aaron", "Bullard");
        $person['age'] = 42;

        $this->assertEquals($person['firstName'], 'Aaron');
        $this->assertEquals($person['age'], 42);
    }

    /** @test */
    public function it_unsets_properties()
    {
        $person = new Human("Aaron", "Bullard");
        $person['age'] = 42;

        unset($person['age']);

        $this->assertFalse(array_key_exists('age', $person->toArray()));
    }

    /** @test */
    public function isset_works_on_public_properties()
    {
        $person = new Human("Aaron", "Bullard");

        $this->assertFalse(isset($person['age']));
        $person['age'] = 42;
        $this->assertTrue(isset($person['age']));
    }
}