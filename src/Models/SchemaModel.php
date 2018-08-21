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

    protected static $_classArgs = [];

    protected $_validator;

    public function __construct(...$args)
    {
        $this->_validator = new Validator;

        $params = static::getConstructorParams(get_class($this));
        $input = array_combine($params, $args);

        parent::__construct($input, ArrayObject::ARRAY_AS_PROPS);

        foreach($this as $offset => $value){
            $this->stopObserving($offset);
            $this->startObserving($offset);
        }

        $this->notify();
    }

    protected static function getConstructorParams($class)
    {
        if(isset(static::$_classArgs[$class]) == false){
            static::$_classArgs[$class] = array_map(function($param){
                return $param->name;
            }, (new ReflectionClass($class))->getConstructor()->getParameters());
        }

        return static::$_classArgs[$class];
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