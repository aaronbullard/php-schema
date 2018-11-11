<?php

namespace PhpSchema\Models;

use ArrayObject;
use PhpSchema\Contracts\Arrayable;
use PhpSchema\Contracts\Observable;
use PhpSchema\Observers\ObserverFactory;
use PhpSchema\Traits\PreventDynamicProperties;
use PhpSchema\ValidationException;

abstract class Model implements Arrayable, Observable
{
    /**
     * Observe and validate child entities
     *
     * @var Boolean
     */
    protected $observe;

    /**
     * Array of all key/value attributes of the instance
     *
     * @var array
     */
    protected $container = [];

    /**
     * Array of attributes that must be observed for changes.  These 
     * entities implement the Observable interface.  When mutated, they notify
     * all subscribers.
     *
     * @var array
     */
    protected $subscribers = [];


    public function __construct($input = [], $observe = TRUE)
    {
        $this->observe = $observe;

        foreach ($input as $offset => $value) {
            $this->stopObserving($offset);
            $this->container[$offset] = $value;
            $this->startObserving($offset);
        }
    }

    protected function containerOffsetExists($offset): bool
    {
        return array_key_exists($offset, $this->container);
    }

    protected function containerGet($offset)
    {
        return $this->container[$offset];
    }

    protected function containerSet($offset, $value): void
    {
        $this->stopObserving($offset);
        
        $this->container[$offset] = $value;

        $this->startObserving($offset);
        
        $this->notify();
    }

    protected function containerPush($value): void
    {
        $offset = count($this->container);
        
        $this->containerSet($offset, $value);
    }

    protected function containerUnset($offset): void
    {
        $this->stopObserving($offset);

        $isAssoc = false;
        if($this->isAssociative()){
            $isAssoc = true;
        }

        unset($this->container[$offset]);

        if($isAssoc === false){
            $this->container = array_values($this->container);
        }

        $this->notify();
    }

    protected function isAssociative()
    {
        if (array() === $this->container) return false;

        return $this->containerKeys() !== range(0, count($this->container) - 1);
    }

    protected function containerKeys()
    {
        return array_keys($this->container);
    }

    protected function shouldObserve(): bool
    {
        return $this->observe;
    }

    protected function startObserving($key): void
    {
        // Should this instance observe child entities?
        if ($this->shouldObserve() === false) {
            return;
        }

        $value = $this->containerGet($key);
        
        if (ObserverFactory::isObservable($value)) {
            $value = ObserverFactory::create($value, $this);
        }

        // Some unknown object
        if (is_object($value) && !($value instanceof Arrayable)) {
            throw ValidationException::ARRAYABLE();
        }

        $this->container[$key] = $value;
    }

    protected function stopObserving($key): void
    {
        if (! $this->containerOffsetExists($key)) {
            return;
        }

        $value = $this->containerGet($key);

        if ($value instanceof Observable) {
            $value->removeSubscriber($this);
        }
    }

    public function addSubscriber(Observable $sub): Observable
    {
        $hash = spl_object_hash($sub);

        $this->subscribers[$hash] = $sub;

        return $this;
    }

    public function removeSubscriber(Observable $sub): Observable
    {
        $key = spl_object_hash($sub);

        unset($this->subscribers[$key]);

        return $this;
    }

    public function notify($payload = null): void
    {
        \array_walk($this->subscribers, function ($sub) use ($payload) {
            $sub->notify($payload);
        });
    }

    public function toArray(): array
    {
        return array_map(function ($value) {
            return $value instanceof Arrayable
                        ? $value->toArray()
                        : $value;
        }, $this->container);
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
