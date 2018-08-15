<?php

namespace PhpSchema;

class ValidationException extends \InvalidArgumentException
{
    protected $errors;

    public static function withErrors(array $errors)
    {
        $properties = array_map(function($error) {
            return $error['property'] == null ? 'unknown': $error['property'];
        }, $errors);

        $self = new static(
            sprintf("There are errors in the following properties: %s", implode(", ", $properties))
        );

        $self->errors = $errors;

        return $self;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}