<?php

namespace PhpSchema\Tests\Entity;

use PhpSchema\Model;

class Contact extends PublicPropertiesModel
{
    protected static $schema = [
        '$ref' => 'file://' . __DIR__ . '/../Schemas/contact.json'
    ];

    public function __construct($person){
        parent::__construct($person);
    }
}