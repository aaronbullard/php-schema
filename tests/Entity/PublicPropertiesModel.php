<?php

namespace PhpSchema\Tests\Entity;

use PhpSchema\Model;
use PhpSchema\Traits\PublicProperties;

class PublicPropertiesModel extends Model
{
    use PublicProperties;
}