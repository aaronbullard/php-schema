<?php

namespace PhpSchema\Traits;

use PhpSchema\Contracts\Observable;

trait Observing
{
    protected $subscribers = [];

    public function addSubscriber(Observable $sub): Observable
    {
        $hash = spl_object_hash($sub);

        $this->subscribers[$hash] = $sub;

        return $this;
    }

    public function removeSubscriber(Observable $sub): Observable
    {
        $id = spl_object_hash($sub);

        unset($this->subscribers[$id]);

        return $this;
    }

    public function notify($payload = null): void
    {
        \array_walk($this->subscribers, function($sub) use ($payload){
            $sub->notify($payload);
        });
    }
}