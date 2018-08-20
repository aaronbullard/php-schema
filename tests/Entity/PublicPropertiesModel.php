<?php

namespace PhpSchema\Tests\Entity;

use PhpSchema\SchemaModel;
use PhpSchema\Traits\PublicProperties;

class PublicPropertiesModel extends SchemaModel
{
    use PublicProperties;
}