<?php

namespace PhpSchema\Tests;

use Mockery;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use PhpSchema\Contracts\Observable;
use PhpSchema\ValidationException;

class TestCase extends PHPUnitTestCase
{
    protected function tearDown()
    {
        Mockery::close();
        parent::tearDown();
    }

    protected function dumpErrors(callable $fn)
    {
        try{
            $fn();
        } catch (ValidationException $e) {
            print_r($e->getErrors());die;
        }
    }

    protected function createModelMock($numOfValidations = 1)
    {
        $val = Mockery::mock(Observable::class);
        $val->shouldReceive('notify')->times($numOfValidations);

        return $val;
    }
}