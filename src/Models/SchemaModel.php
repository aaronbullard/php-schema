<?php

namespace PhpSchema\Models;

use PhpSchema\Validator;
use PhpSchema\ValidationException;

abstract class SchemaModel extends Model
{
    protected static $schema;

    protected $validator;

    public function __construct($input = [])
    {
        $this->validator = new Validator;

        parent::__construct($input);

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

    /**
     * Validate the instance
     *
     * @throws ValidationException
     * @return void
     */
    public function validate(): void
    {
        $obj = $this->toObject();

        $this->validator->validate($obj, $this->getSchema());

        if ($this->validator->isValid() === false) {
            throw ValidationException::withErrors(
                $this->validator->getErrors()
            );
        }
    }
}
