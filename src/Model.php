<?php

namespace PhpSchema;

use ReflectionClass;
use PhpSchema\Traits\Observing;
use PhpSchema\Traits\ConvertsType;
use PhpSchema\Contracts\Arrayable;
use PhpSchema\Contracts\Observable;
use PhpSchema\Traits\PublicProperties;
use PhpSchema\Observers\ArrayObserver;
use PhpSchema\Observers\ObjectObserver;
use PhpSchema\Observers\ObserverFactory;

abstract class Model implements Arrayable, Observable
{
    use Observing, ConvertsType;

    protected static $schema;

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

        // Get parameters from constructor
        $params = array_map(function($param){
            return $param->name;
        }, (new ReflectionClass($this))->getConstructor()->getParameters());

        $this->hydrate(array_combine($params, $args));
        $this->validate();
    }

    public function getSchema()
    {
        return static::$schema;
    }

    protected function setAttribute($key, $value)
    {
        if(ObserverFactory::isObservable($value)){
            $value = ObserverFactory::create($value, $this);
        }

        $this->_attributes[$key] = $value;

        $this->validate();
        $this->notify();
    }

    protected function getAttribute($key)
    {
        return $this->_attributes[$key];
    }

    public function hydrate(array $data)
    {
        foreach($data as $key => $value){
            if(ObserverFactory::isObservable($value)){
                $value = ObserverFactory::create($value, $this);
            }
    
            $this->_attributes[$key] = $value;
        }

        $this->notify();
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