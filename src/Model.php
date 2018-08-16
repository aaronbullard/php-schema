<?php

namespace PhpSchema;

use ReflectionClass;
use JsonSchema\Validator;
use PhpSchema\Contracts\Arrayable;
use PhpSchema\Traits\PublicProperties;

abstract class Model implements Arrayable
{
    protected static $schema;
    
    protected $_attributes;

    protected $_validator;

    public function __construct(...$args)
    {
        $this->_validator = new Validator;
        $this->_attributes = [];

        $params = $this->getConstructorParameters();
        $this->hydrate(array_combine($params, $args));
        $this->validate();
    }

    protected function getConstructorParameters()
    {
        $refl = new ReflectionClass($this);

        return array_map(function($param){
            return $param->name;
        }, $refl->getConstructor()->getParameters());
    }

    public function getSchema()
    {
        return static::$schema;
    }

    protected function set($key, $value)
    {
        if(is_array($value)){
            $value = new ArrayObserver($value, $this);
        }

        $this->_attributes[$key] = $value;
    }

    public function hydrate(array $data)
    {
        foreach($data as $key => $value){
            $this->set($key, $value);
        }
    }

    public function validate()
    {
        $object = $this->toObject();

        $this->_validator->validate($object, $this->getSchema());

        if(!$this->_validator->isValid()){
            $errors = $this->_validator->getErrors();
            throw ValidationException::withErrors($errors);
        }
    }

    public function toArray(): array
    {
        return array_map(function($value){
            return $this->clean($value);
        }, $this->_attributes);
    }

    protected function clean($value)
    {
        if($value instanceof ArrayObserver){
            $value = $value->toArray();
        }

        if(is_array($value)){
            return array_map(function($v){
                return $this->clean($v);
            }, $value);
        }

        if($value instanceof Arrayable){
            return $value->toArray();
        }

        if($value instanceof \StdClass){
            return json_decode(json_encode($value), true);
        }

        // Some unknown object, make null
        if(is_object($value)){
            return null;
        }

        return $value;
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    public function toObject()
    {
        return json_decode($this->toJson());
    }

    public function __toString(): string
    {
        return $this->toJson();
    }

}