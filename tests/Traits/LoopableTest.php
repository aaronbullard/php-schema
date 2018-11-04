<?php

namespace PhpSchema\Tests\Traits;

use Iterator;
use PhpSchema\Tests\TestCase;
use PhpSchema\Models\Model;
use PhpSchema\Traits\Loopable;

class Looper extends Model implements Iterator
{
    use Loopable;

    protected static $schema = [
        '$ref' => 'file://' . __DIR__ . '/../Schemas/person.json'
    ];

    public function __construct($firstName, $lastName)
    {
        parent::__construct(compact('firstName', 'lastName'));
    }
}

class LoopableTest extends TestCase
{
    protected $looper;

    protected function setUp()
    {
        parent::setUp();

        $this->looper = new Looper("Aaron", "Bullard");
    }

    /** @test */
    public function it_loops_in_a_foreach()
    {
        $count = 0;
        $keys = [];
        foreach($this->looper as $k => $v){
            $keys[] = $k;
            $count++;
        }

        $this->assertEquals(2, $count);
        $this->assertEquals($keys, ['firstName', 'lastName']);
    }
}