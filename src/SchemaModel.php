<?php

namespace PhpSchema;

use ReflectionClass;
use PhpSchema\Traits\ConvertsType;
use PhpSchema\Contracts\Arrayable;
use PhpSchema\Traits\PublicProperties;
use PhpSchema\Observers\ObserverFactory;

abstract class SchemaModel extends Model implements Arrayable
{
    use ConvertsType;

    protected static $schema;

    protected static $_classArgs = [];

    protected $_validator;
    
    protected $_attributes;

    /**
     * Child classes must implement constructor if the schema contains required properties.
     * Parameter names must match the names (case-sensitive) as described in the schema.
     *
     * @param mixed ...$args
     */
    public function __construct(...$args)
    {
        $this->_validator = new Validator;
        $this->_attributes = [];

        $params = static::getConstructorParams(get_class($this));

        $this->hydrate(array_combine($params, $args));
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

    protected function hydrate(array $data)
    {
        foreach($data as $key => $value){
            $this->stopObserving($key);

            $this->setAttribute($key, $value);

            $this->startObserving($key);
        }

        $this->notify();
    }

    protected function getAttribute($key)
    {
        return $this->_attributes[$key];
    }

    protected function setAttribute($key, $value): void
    {
        $this->_attributes[$key] = $value;
    }

    protected function unsetAttribute($key): void
    {
        unset($this->_attributes[$key]);
    }

    protected function keyExists($key)
    {
        return isset($this->_attributes[$key]);
    }

    public function validate(): void
    {
        $object = $this->toObject();

        $this->_validator->validate($object, $this->getSchema());

        if(!$this->_validator->isValid()){
            $errors = $this->_validator->getErrors();
            throw ValidationException::withErrors($errors);
        }
    }

    public function notify($payload = null): void
    {
        $this->validate();

        \array_walk($this->subscribers, function($sub) use ($payload){
            $sub->notify($payload);
        });
    }

    protected function clean($value)
    {
        $value = $value instanceof Arrayable 
                    ? $value->toArray() 
                    : $value;

        // Some unknown object
        if(is_object($value)){
            throw ValidationException::ARRAYABLE();
        }

        return $value;
    }

    public function toArray(): array
    {
        return array_map(function($value){
            return $this->clean($value);
        }, $this->_attributes);
    }
}