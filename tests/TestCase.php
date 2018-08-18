<?php

namespace PhpSchema\Tests;

use Mockery;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use PhpSchema\Model;
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
        $val = Mockery::mock(Model::class);
        $val->shouldReceive('validate')->times($numOfValidations);

        return $val;
    }
}