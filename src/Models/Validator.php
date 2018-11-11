<?php

namespace PhpSchema\Models;

use JsonSchema\Validator as JsonSchemaValidator;


class Validator extends JsonSchemaValidator
{
    public static function create(): Validator
    {
        return new static();
    }
}
