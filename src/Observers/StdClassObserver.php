<?php

namespace PhpSchema\Observers;

use StdClass;
use PhpSchema\Contracts\Observable;
use PhpSchema\Traits\PublicProperties;

class StdClassObserver extends Observer
{
    use PublicProperties;
}