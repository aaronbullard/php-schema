<?php

namespace PhpSchema\Tests\Traits;

use PhpSchema\Tests\TestCase;
use PhpSchema\Traits\Observing;
use PhpSchema\Contracts\Observable;
use PhpSchema\ValidationException;

class ObservingTest extends TestCase
{
    protected function createObservable()
    {
        return new class() implements Observable {
            use Observing;

            public function getSubscribers()
            {
                return $this->subscribers;
            }
        };
    }

    /** @test */
    public function it_removes_a_subscriber()
    {
        $parent = $this->createObservable();
        $child = $this->createObservable();

        $child->addSubscriber($parent);
        $this->assertCount(1, $child->getSubscribers());

        $child->removeSubscriber($parent);
        $this->assertCount(0, $child->getSubscribers());
    }
}