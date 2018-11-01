<?php

namespace PhpSchema\Tests;

use Mockery, Closure;
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

    protected function assertUndefinedIndex($obj, $index)
    {
        $count = 0;
        try {
            $obj->$index;
        }catch(\Throwable $e){
            $msg_parts = explode(' ', $e->getMessage());
            $testMsg = "Error message was not 'Undefined index: " . $index . "'.";
            $this->assertEquals($msg_parts[0], 'Undefined', $testMsg);
            $this->assertEquals($msg_parts[1], 'index:', $testMsg);
            $this->assertEquals($msg_parts[2], $index, $testMsg);
            $count++;
        }
        $this->assertEquals($count, 1, "TestCase::assertUndefinedIndex failed to throw an error.");
    }
}