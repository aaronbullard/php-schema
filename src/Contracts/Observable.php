<?php

namespace PhpSchema\Contracts;

interface Observable
{
    public function addSubscriber(Observable $sub): Observable;

    public function removeSubscriber(Observable $sub): Observable;
    
    public function notify($payload = null): void;
}