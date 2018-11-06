<?php

namespace PhpSchema\Observers;

use Iterator;
use PhpSchema\Traits\Loopable;
use PhpSchema\Contracts\Observable;
use PhpSchema\Traits\PublicProperties;

class StdClassObserver extends Observer implements Iterator
{
    use PublicProperties, Loopable;
}
