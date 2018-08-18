<?php

namespace PhpSchema\Test\Observers;

use PhpSchema\Tests\TestCase;
use PhpSchema\Contracts\Arrayable;
use PhpSchema\Observers\StdClassObserver;

class StdClassObserverTest extends TestCase
{
    protected $obj;

    protected function setUp()
    {
        parent::setUp();
        $this->obj = json_decode(json_encode([
            'name' => 'Don',
            'child' => [
                'name' => 'Lynn',
                'child' => [
                    'name' => 'Aaron'
                ]
            ]
        ]), false);
    }

    /** @test */
    public function it_validates_on_set()
    {
        $obs = new StdClassObserver($this->obj, $this->createModelMock(1));
        $this->assertEquals('Don', $obs->name);
        
        $obs->name = 'JD';
        $this->assertEquals('JD', $obs->name);
    }

    /** @test */
    public function it_validates_on_unset()
    {
        $obs = new StdClassObserver($this->obj, $this->createModelMock(2));
        
        $grandchild = $this->obj->child->child;
        $grandchild->name = "A-A-ron"; // one validation

        unset($this->obj->child->child); // two times
        $grandchild->name = "Aaron"; // no validation runs

        $this->assertEquals(null, $this->obj->child->child);
    }

    /** @test */
    public function it_validates_on_unset_by_pointer()
    {
        $obs = new StdClassObserver($this->obj, $this->createModelMock(2));
        
        $grandchild = $this->obj->child->child;
        $grandchild->name = "A-A-ron"; // one validation

        $this->obj->child->child = (object)['name' => 'Bob']; // two times
        $grandchild->name = "Aaron"; // no validation runs

        $this->assertEquals("Bob", $this->obj->child->child->name);
    }

    /** @skip */
    // public function it_validates_after_method_call()
    // {
    //     $obj = new class() implements Arrayable {
    //         public function getArgs($one, $two, $three) { return compact('one', 'two', 'three'); }
    //         public function toArray (): array { return []; }
    //     };

    //     $obs = new StdClassObserver($obj, $this->createModelMock(3));

    //     $this->assertEquals(0, $obs->getArgs(0, 1, 2)['one']);
    //     $this->assertEquals(1, $obs->getArgs(0, 1, 2)['two']);
    //     $this->assertEquals(2, $obs->getArgs(0, 1, 2)['three']);
    // }

    /** @test */
    public function it_watches_embedded_objects()
    {
        $obs = new StdClassObserver($this->obj, $this->createModelMock(1));
        
        $this->assertInstanceOf(StdClassObserver::class, $obs->child->child);
        $this->assertEquals('Aaron', $obs->child->child->name);

        $obs->child->child->name = 'James';
        $this->assertEquals('James', $obs->child->child->name);
    }

    /** @test */
    public function it_casts_to_array()
    {
        $obs = new StdClassObserver($this->obj, $this->createModelMock(0));
        $this->assertEquals('Aaron', $obs->toArray()['child']['child']['name']);
    }

    /** @test */
    public function it_validates_for_two_parents()
    {
        $parent1 = $this->createModelMock(1);
        $parent2 = $this->createModelMock(1);

        $obs = new StdClassObserver($this->obj, $parent1);
        $obs->addSubscriber($parent2);

        $obs->child->child->name = 'James';
        $this->assertEquals('James', $obs->child->child->name);
    }

}