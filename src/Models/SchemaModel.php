<?php

namespace PhpSchema\Models;

use ArrayObject, ReflectionClass;
use PhpSchema\Validator;
use PhpSchema\Traits\Observing;
use PhpSchema\Traits\ConvertsType;
use PhpSchema\Contracts\Arrayable;
use PhpSchema\Contracts\Observable;
use PhpSchema\ValidationException;

abstract class SchemaModel extends Model implements Arrayable, Observable
{
    use Observing, ConvertsType;

    protected static $schema;

    protected $_validator;

    public function __construct($input)
    {
        $this->_validator = new Validator;

        parent::__construct($input, ArrayObject::ARRAY_AS_PROPS);

        foreach($this as $offset => $value){
            $this->stopObserving($offset);
            $this->startObserving($offset);
        }

        $this->notify();
    }

    public function getSchema()
    {
        return static::$schema;
    }

    public function notify($payload = null): void
    {
        $this->validate();

        \array_walk($this->subscribers, function($sub) use ($payload){
            $sub->notify($payload);
        });
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