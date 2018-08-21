<?php

namespace PhpSchema\Models;

use ArrayObject;
use PhpSchema\Validator;
use PhpSchema\ValidationException;

abstract class SchemaModel extends Model
{
    protected static $schema;

    protected $_validator;

    public function __construct($input)
    {
        $this->_validator = new Validator;

        parent::__construct($input, ArrayObject::ARRAY_AS_PROPS);

        $this->notify();
    }

    public function getSchema()
    {
        return static::$schema;
    }

    public function notify($payload = null): void
    {
        $this->validate();

        parent::notify($payload);
    }

    public function validate(): void
    {
        $object = $this->toObject();

        $this->_validator->validate($object, $this->getSchema());

        if($this->_validator->isValid() == false){
            $errors = $this->_validator->getErrors();
            throw ValidationException::withErrors($errors);
        }
    }

}