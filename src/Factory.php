<?php

namespace PhpSchema;

use PhpSchema\Models\SchemaModel;
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
        return new class($args, $schema) extends SchemaModel
        {
            use PublicProperties;
            
            protected static $schema;

            public function __construct($args, $schema)
            {
                static::$schema = $schema;
                parent::__construct($args);
            }
        };
    }
}
