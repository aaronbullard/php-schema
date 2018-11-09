<?php

namespace PhpSchema\Tests;

use PhpSchema\Factory;
use PhpSchema\Validator;
use PhpSchema\ValidationException;

class ValidatorTest extends TestCase
{
    /** @test */
    public function it_constructs_statically()
    {
        $validator = Validator::create();

        $this->assertInstanceOf(Validator::class, $validator);
    }
}