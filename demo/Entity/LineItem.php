<?php

namespace PhpSchema\Demo\Entity;

use PhpSchema\Models\SchemaModel;
use PhpSchema\Traits\PublicProperties;

class LineItem extends SchemaModel
{
    use PublicProperties;
    
    protected static $schema = [
        '$ref' => 'file://' . __DIR__ . '/../Schemas/line_item.json'
    ];

    public function __construct(string $product, $amount)
    {
        parent::__construct(compact('product', 'amount'));
    }
}