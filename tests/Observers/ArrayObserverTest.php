<?php

namespace PhpSchema\Test\Observers;

use StdClass;
use PhpSchema\Tests\TestCase;
use PhpSchema\Contracts\Arrayable;
use PhpSchema\Observers\ArrayObserver;

class ArrayObserverTest extends TestCase
{
    /** @test */
    public function it_observes_set_mutation()
    {
        $numOfValidations = 4;
        $obs = new ArrayObserver([], $this->createModelMock($numOfValidations));

        $obs[] = 'hello world'; // 1
        $obs[] = 'hello world'; // 2
        $obs[] = 'hello world'; // 3
        
        $this->assertCount(3, $obs);
        $this->assertEquals('hello world', $obs[2]);

        foreach($obs as $val){
            $this->assertEquals('hello world', $val);
        }

        unset($obs[1]); // 4
    }

    /** @test */
    public function it_observes_item_mutation()
    {
        $numOfValidations = 3;
        $obs = new ArrayObserver([], $this->createModelMock($numOfValidations));

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

        $obs[] = new StdClass; // 1
        $obs[] = new StdClass; // 2

        $aaron = $obs[0];
        $aaron->name = "Aaron"; // 3

        unset($obs[0]); // 4
        $aaron->name = "A-A-ron"; // nothing happens

        $bob = $obs[1];
        $obs[1] = new StdClass; // 5
        $bob->name = "Bob"; // nothing happens

        $this->assertCount(1, $obs);
    }

    /** @test */
    public function it_is_arrayable()
    {
        $obs = new ArrayObserver([
            'firstName' => "Aaron",
            'lastName' => "Bullard"
        ], $this->createModelMock(0));

        $this->assertInstanceOf(Arrayable::class, $obs);
        
        $arr = $obs->toArray();
        $this->assertTrue(is_array($arr));
        $this->assertEquals("Aaron", $arr['firstName']);
    }

}