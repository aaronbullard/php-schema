<?php

namespace PhpSchema\Contracts;

interface Verifiable
{
    /**
     * Validate scheam
     *
     * @return void
     * @throws PhpSchema\ValidationException
     */
    public function validate(): void;
}