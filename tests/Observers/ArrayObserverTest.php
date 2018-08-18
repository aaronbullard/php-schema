<?php

namespace PhpSchema\Test\Observers;

use PhpSchema\Tests\TestCase;
use PhpSchema\Contracts\Arrayable;
use PhpSchema\Observers\ArrayObserver;

class ArrayObserverTest extends TestCase
{
    /** @test */
    public function it_validates_on_mutation()
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