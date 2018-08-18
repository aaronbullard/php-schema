<?php

namespace PhpSchema\Tests;

use PhpSchema\Factory;
use PhpSchema\ValidationException;

class FactoryTest extends TestCase
{
    protected $schema;

    protected $person;

    protected function setUp()
    {
        parent::setUp();
        $this->schema = ['$ref' => 'file://' . __DIR__ . '/Schemas/person.json'];
        $this->person = Factory::createDTO($this->schema, [
            'firstName' => "Aaron",
            'lastName' => "Bullard"
        ]);
    }

    /** @test */
    public function it_creates_a_dynamic_class_instance()
    {
        $this->assertEquals($this->schema, $this->person->getSchema());
        $this->assertEquals("Aaron", $this->person->firstName);
        $this->assertEquals("Bullard", $this->person->lastName);
    }

    /** @test */
    public function it_validates_properties()
    {
        $this->person->age = 42;
        $this->assertEquals(42, $this->person->age);
        $this->expectException(ValidationException::class);
        $this->person->age = "forty-two";
    }

    /** @test */
    public function it_validates_constructor_for_required_properties()
    {
        $this->expectException(ValidationException::class);

        Factory::createDTO($this->schema, [
            'firstName' => "Aaron"
        ]);
    }
}