<?php

namespace PhpSchema\Tests;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use PhpSchema\ValidationException;

class TestCase extends PHPUnitTestCase
{
    protected function dumpErrors(callable $fn)
    {
        try{
            $fn();
        } catch (ValidationException $e) {
            print_r($e->getErrors());die;
        }
    }
}