<?php

namespace PhpSchema\Tests\Models;

use PhpSchema\Tests\TestCase;
use PhpSchema\Models\Model;
use PhpSchema\Tests\Entity\Person;
use PhpSchema\Contracts\Observable;
use PhpSchema\Traits\PublicProperties;

class UnObservantPerson extends Model
{
    use PublicProperties;

    protected $schema = [
        '$ref' => 'file://' . __DIR__ . '/../Schemas/person.json'
    ];

    public function __construct($firstName, $lastName)
    {
        parent::__construct(compact('firstName', 'lastName'), false);
    }
}

class ModelTest extends TestCase
{
    /** @test */
    public function it_observes_children()
    {
        $person = new Person('Aaron', 'Bullard');
        $person->phoneNumber = (object)['number' => '843-867-5309'];

        $this->assertInstanceOf(Observable::class, $person->phoneNumber);
    }

    /** @test */
    public function it_ignores_children()
    {
        $person = new UnObservantPerson('Aaron', 'Bullard');
        $person->phoneNumber = (object)['number' => '843-867-5309'];

        $this->assertNotInstanceOf(Observable::class, $person->phoneNumber);

        unset($person->phoneNumber);
    }
}