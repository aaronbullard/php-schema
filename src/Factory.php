<?php

namespace PhpSchema;

use ArrayObject;
use PhpSchema\Models\Model;
use PhpSchema\Traits\PublicProperties;

class Factory
{
    /**
     * Creates a DTO that enforces schema type validation
     * while using public properties.
     *
     * @param array $schema
     * @param array $args
     * @return object Anonymous Class
     */
    public static function createDTO($schema, array $args = [])
    {
        return new class($args, $schema) extends Model
        {
            // use PublicProperties;

            protected static $schema;

            protected $_validator;

            public function __construct($args, $schema)
            {
                static::$schema = $schema;
                $this->_validator = new Validator;

                parent::__construct($args, ArrayObject::ARRAY_AS_PROPS);

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
        };
    }
}
