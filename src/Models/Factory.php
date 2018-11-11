<?php

namespace PhpSchema\Models;

// use PhpSchema\Models\SchemaModel;
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
    public static function createDTO($schema, array $args = []): object
    {
        return new class($schema, $args) extends SchemaModel
        {
            use PublicProperties;
            
            protected static $schema;

            public function __construct($schema, $args)
            {
                static::$schema = $schema;
                parent::__construct($args);
            }
        };
    }
}
