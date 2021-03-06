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

class Collection extends Model implements ArrayAccess
{
    use ArrayAccessible;

    protected static $schema = [
        "type" => "array"
    ];
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
    public function it_resets_array_keys_on_unset_for_non_associative_arrays()
    {
        // Non Associative Array - reset Keys
        $collection = new Collection();
        $collection[0] = 0;
        $collection[1] = 1;
        $collection[2] = 2;

        // Keys are reset
        unset($collection[1]);
        $this->assertTrue(array_key_exists(1, $collection->toArray())); // [0 => 0, 1 => 1];

        // Associative Array
        $collection = new Collection();
        $collection['first'] = 0;
        $collection['second'] = 1;
        $collection['third'] = 2;

        // Keys are NOT reset
        unset($collection['second']);
        $this->assertFalse(array_key_exists('second', $collection->toArray()));
        $this->assertTrue(array_key_exists('third', $collection->toArray()));
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