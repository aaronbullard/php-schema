<?php

namespace PhpSchema\Tests\Observers;

use StdClass;
use PhpSchema\Tests\TestCase;
use PhpSchema\Tests\Entity\Person;
use PhpSchema\Contracts\Arrayable;
use PhpSchema\Observers\ArrayObserver;

class ArrayObserverTest extends TestCase
{
    /** @test */
    public function it_observes_set_mutation()
    {
        $obs = new ArrayObserver([], $this->createModelMock(4));

        $obs[] = 'hello world'; // 1
        $obs[] = 'hello world'; // 2
        $obs[] = 'hello world'; // 3
        
        $this->assertCount(3, $obs);
        $this->assertEquals('hello world', $obs[2]);

        foreach($obs as $val){
            $this->assertEquals('hello world', $val);
        }

        unset($obs[1]); // 4
        $this->assertCount(2, $obs);
    }

    /** @test */
    public function it_implements_push()
    {
        $obs = new ArrayObserver([], $this->createModelMock(3));

        $obs->push('hello world'); // 1
        $obs->push('hello world'); // 2
        $obs->push('hello world'); // 3
        
        $this->assertCount(3, $obs);
    }

    /** @test */
    public function it_implements_unset_as_a_function()
    {
        $obs = new ArrayObserver([], $this->createModelMock(4));

        $obs['one'] = true; // 1
        $obs['two'] = true; // 2
        $obs['three'] = true; // 3

        $obs->unset('two'); // 4

        $this->assertCount(2, $obs);
        $this->assertFalse(isset($obs['two']));
    }

    /** @test */
    public function it_implements_filter()
    {
        $obs = new ArrayObserver([], $this->createModelMock(3));

        $obs->push('one'); // 1
        $obs->push('two'); // 2
        $obs->push('three'); // 3

        $filtered = $obs->filter(function ($item) {
            return $item != 'two';
        });

        $this->assertCount(2, $filtered);
    }

    /** @test */
    public function it_observes_item_mutation()
    {
        $obs = new ArrayObserver([], $this->createModelMock(3));

        $obs[] = new StdClass; // 1
        $obs[] = new StdClass; // 2

        $obs[0]->name = "Aaron"; // 3

        $this->assertEquals("Aaron", $obs[0]->name);
    }

    /** @test */
    public function it_observes_unsetting()
    {
        $numOfValidations = 5;
        $obs = new ArrayObserver([], $this->createModelMock($numOfValidations));

        $obs['aaron'] = new StdClass; // 1
        $obs['bob'] = new StdClass; // 2

        $aaron = $obs['aaron'];
        $aaron->name = "Aaron"; // 3

        // unset by offset
        unset($obs['aaron']); // 4
        $aaron->name = "A-A-ron"; // nothing happens

        // unset by replace
        $bob = $obs['bob'];
        $obs['bob'] = new StdClass; // 5
        $bob->name = "Bob"; // nothing happens

        $this->assertCount(1, $obs);
    }

    /** @test */
    public function it_subscribes_to_children()
    {
        $obs = new ArrayObserver([
            'firstName' => "Aaron",
            'lastName' => "Bullard",
            'model' => new Person("Aaron", "Bullard"),
            'stdclass' => (object)['firstName' => "Aaron", 'lastName' => "Bullard"],
            'array' => [1, 2, 3]
        ], $this->createModelMock(3));

        $obs['model']->firstName = "A-A-ron"; // 1
        $obs['stdclass']->firstName = "A-A-ron"; // 2
        $obs['array'][1] = '2'; // 3

        $this->assertCount(5, $obs);
    }

    /** @test */
    public function it_is_arrayable()
    {
        $obs = new ArrayObserver([
            'firstName' => "Aaron",
            'lastName' => "Bullard",
            'model' => new Person("Aaron", "Bullard"),
            'stdclass' => (object)['firstName' => "Aaron", 'lastName' => "Bullard"],
            'array' => [1, 2, 3]
        ], $this->createModelMock(0));

        $this->assertInstanceOf(Arrayable::class, $obs);
        
        $arr = $obs->toArray();
        $this->assertTrue(is_array($arr));
        $this->assertEquals("Aaron", $arr['firstName']);
        $this->assertEquals("Aaron", $arr['model']['firstName']);
        $this->assertEquals("Aaron", $arr['stdclass']['firstName']);
        $this->assertEquals("2", $arr['array'][1]);
    }

    /** @test */
    public function it_prevents_access_as_props()
    {
        $obs = new ArrayObserver([
            'firstName' => "Aaron"
        ], $this->createModelMock(0));

        $this->expectException(\PHPUnit\Framework\Error\Notice::class);
        $obs->firstName;
    }

}