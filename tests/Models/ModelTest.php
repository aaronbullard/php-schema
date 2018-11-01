<?php

namespace PhpSchema\Tests\Models;

use PhpSchema\Tests\TestCase;
use PhpSchema\Models\Model;

class ImplModel extends Model{}

class AbstractModelTest extends TestCase
{
    /** @test */
    public function it_behaves_like_array_object()
    {
        $person = new ImplModel(['fname' => 'Aaron']);
        $person['lname'] = 'Bullard';

        $this->assertEquals($person['fname'], 'Aaron');
        $this->assertEquals($person['lname'], 'Bullard');
    }

}