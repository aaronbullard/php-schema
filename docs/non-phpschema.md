# Integrating with non-PhpSchema Classes

While the root object must be an instance of PhpSchema, nested objects need only to implement the PhpSchema\Contracts\Arrayable interface.  Schema validation will be based on the returned associative array.

```php
<?php

namespace PhpSchema\Contracts;

interface Arrayable
{
    public function toArray(): array;
}
```

### Example
```php
<?php

use PhpSchema\Contracts\Arrayable;

class PhoneNumber implements Arrayable
{
    protected $number;

    public function __construct($number)
    {
        $this->number = $number;
    }

    public function number()
    {
        return $this->number;
    }

    public function toArray(): array
    {
        return ['number' => $this->number];
    }
}

// It validates nested objects
$person = new Person("John", "Smith");
$phoneNumber = new PhoneNumber("843-867-5309"); // instance of Arrayable
$person->phoneNumber = $phoneNumber;

$person->toJson(); // {"firstName":"John","lastName":"Smith","phoneNumber":{"number":"843-867-5309"}}
```

Integrates with StdClass
```php
// It validates nested objects of type StdClass
$person = new Person("John", "Smith");
$phoneNumber = new StdClass();
$phoneNumber->number = "843-867-5309";
$person->phoneNumber = $phoneNumber;

$person->toJson(); // {"firstName":"John","lastName":"Smith","phoneNumber":{"number":"843-867-5309"}}

```

####
[Back](./../readme.md)