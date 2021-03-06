<?php

namespace PhpSchema\Tests\Observers;

use PhpSchema\Tests\TestCase;
use PhpSchema\Contracts\Arrayable;
use PhpSchema\Observers\StdClassObserver;

class StdClassObserverTest extends TestCase
{
    protected $obj;

    protected function setUp()
    {
        parent::setUp();
        $this->obj = (object)[
            'name' => 'Don',
            'child' => (object)[
                'name' => 'Lynn',
                'child' => (object)[
                    'name' => 'Aaron'
                ]
            ]
        ];
    }

    /** @test */
    public function it_validates_on_set()
    {
        $obs = new StdClassObserver($this->obj, $this->createModelMock(1));

        $obs->name = 'JD'; // one validation
        $this->assertEquals('JD', $obs->name);
    }

    /** @test */
    public function it_validates_on_unset()
    {
        $obs = new StdClassObserver($this->obj, $this->createModelMock(2));
        
        $grandchild = $obs->child->child;
        $grandchild->name = "A-A-ron"; // one validation

        unset($obs->child->child); // two times, validates after unset
        $grandchild->name = "Aaron"; // no validation runs

        $this->assertFalse(isset($obs->child->child));
    }

    /** @test */
    public function it_validates_on_unset_by_pointer()
    {
        $obs = new StdClassObserver($this->obj, $this->createModelMock(2));
        
        $grandchild = $obs->child->child;
        $grandchild->name = "A-A-ron"; // one validation

        $obs->child->child = (object)['name' => 'Bob']; // two times
        $grandchild->name = "Aaron"; // no validation runs

        $this->assertEquals("Bob", $obs->child->child->name);
    }

    /** @test */
    public function it_watches_embedded_objects()
    {
        $obs = new StdClassObserver($this->obj, $this->createModelMock(1));
        $this->assertInstanceOf(StdClassObserver::class, $obs->child->child);
        $this->assertEquals('Aaron', $obs->child->child->name);

        $obs->child->child->name = 'James'; // one validation
        $this->assertEquals('James', $obs->child->child->name);
    }

    /** @test */
    public function it_prevents_outside_mutation_once_passed()
    {
        $obs = new StdClassObserver($this->obj, $this->createModelMock(1));

        $newChild = (object)['name' => 'Test'];

        $obs->child->child = $newChild; // one validation, $newChild is cloned

        $newChild->name = 'Changed';

        $this->assertEquals('Test', $obs->child->child->name);
        $this->assertEquals('Changed', $newChild->name);
    }

    /** @test */
    public function it_validates_for_two_parents()
    {
        $parent1 = $this->createModelMock(1);
        $parent2 = $this->createModelMock(1);

        $obs = new StdClassObserver($this->obj, $parent1);
        $obs->addSubscriber($parent2);

        $obs->child->child->name = 'James'; // one AND two validations
        $this->assertEquals('James', $obs->child->child->name);
    }

    /** @test */
    public function it_casts_to_array()
    {
        $obs = new StdClassObserver($this->obj, $this->createModelMock(0));
        $this->assertEquals('Aaron', $obs->toArray()['child']['child']['name']);
    }

    /** @test */
    public function it_is_iterable()
    {
        $obj = new \StdClass();
        $obj->one = 'one';
        $obj->two = 'two';
        $obj->three = 'three';

        $obs = new StdClassObserver($obj, $this->createModelMock(0));

        $result = "";
        foreach($obs as $key => $value){
            $result .= $value;
        }
        
        $this->assertEquals("onetwothree", $result);
    }

    /** @test */
    public function it_prevents_arrayable_access()
    {
        $obs = new StdClassObserver($this->obj, $this->createModelMock(0));

        $this->expectException(\Error::class);
        $obs['name'];
    }
}