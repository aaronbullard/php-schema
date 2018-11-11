<?php

namespace PhpSchema\Tests\Models;

use PhpSchema\Tests\TestCase;
use PhpSchema\Models\Validator;

class ValidatorTest extends TestCase
{
    /** @test */
    public function it_constructs_statically()
    {
        $validator = Validator::create();

        $this->assertInstanceOf(Validator::class, $validator);
    }
}