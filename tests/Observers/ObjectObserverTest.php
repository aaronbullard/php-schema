<?php

namespace PhpSchema\Test\Observers;

use PhpSchema\Tests\TestCase;
use PhpSchema\Contracts\Arrayable;
use PhpSchema\Observers\ObjectObserver;

class ObjectObserverTest extends TestCase
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
        $obs = new ObjectObserver($this->obj, $this->createModelMock(1));
        $this->assertEquals('Don', $obs->name);
        
        $obs->name = 'JD';
        $this->assertEquals('JD', $obs->name);
    }

    /** @test */
    public function it_validates_after_method_call()
    {
        $obj = new class() implements Arrayable {
            public function getArgs($one, $two, $three) { return compact('one', 'two', 'three'); }
            public function toArray (): array { return []; }
        };

        $obs = new ObjectObserver($obj, $this->createModelMock(3));

        $this->assertEquals(0, $obs->getArgs(0, 1, 2)['one']);
        $this->assertEquals(1, $obs->getArgs(0, 1, 2)['two']);
        $this->assertEquals(2, $obs->getArgs(0, 1, 2)['three']);
    }

    /** @test */
    public function it_watches_embedded_objects()
    {
        $obs = new ObjectObserver($this->obj, $this->createModelMock(1));
        
        $this->assertInstanceOf(ObjectObserver::class, $obs->child->child);
        $this->assertEquals('Aaron', $obs->child->child->name);

        $obs->child->child->name = 'James';
        $this->assertEquals('James', $obs->child->child->name);
    }
}