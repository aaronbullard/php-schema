<?php

namespace PhpSchema\Tests\Traits;

use PhpSchema\Tests\TestCase;
use PhpSchema\Traits\PreventDynamicProperties;

class Person extends \ArrayObject
{
    use PreventDynamicProperties;
}

class PreventDynamicPropertiesTest extends TestCase
{
    /** @test */
    public function it_allows_only_array_access()
    {
        $bob = new Person([], 0);
        $bob['name'] = 'Aaron';
        
        // Acts like an array
        $this->assertEquals($bob['name'], "Aaron");
        $this->assertEquals($bob->getArrayCopy(), [
            'name' => 'Aaron'
        ]);
    }

    /** @test */
    public function it_prevents_prop_access()
    {
        $bob = new Person([], 0);
        $bob['name'] = 'Aaron';
        $this->expectException(\PHPUnit\Framework\Error\Warning::class);
        $name = $bob->name;
    }

    /** @test */
    public function it_prevents_prop_assignment()
    {
        $bob = new Person([], 0);
        $bob['name'] = 'Aaron';
        $this->expectException(\PHPUnit\Framework\Error\Warning::class);
        $bob->age = 40;
    }

    /** @test */
    public function it_allows_prop_assignment()
    {
        $bob = new Person([], 2);
        $bob['name'] = 'Aaron';
        $bob->age = 40;
        $this->assertEquals($bob->getArrayCopy(), [
            'name' => 'Aaron',
            'age' => 40
        ]);
    }

    /** @test */
    public function it_allows_prop_access()
    {
        $bob = new Person([], 2);
        $bob['name'] = 'Aaron';
        $this->assertEquals($bob->name, 'Aaron');
    }
}