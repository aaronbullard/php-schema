# PhpSchema
Self validating PHP objects from JSON Schema

## Installation

### Library

```bash
git clone https://github.com/aaronbullard/php-schema.git
```

### Composer

[Install PHP Composer](https://getcomposer.org/doc/00-intro.md)

```bash
composer require aaronbullard/php-schema
```

## Usage

PhpSchema provides a quick solution to roll objects from JSON Schema.

Objects self validate during construction and when mutated.

In keeping with DDD principles, PhpSchema objects should always be in a valid state.  Therefore all required properties must be passed through the constructor.  Any optional properties can be passed after instantiation.

Validation is built off of JsonSchema\Validator from justinrainbow/json-schema


```php
<?php

use PhpSchema\Model;
use PhpSchema\Traits\MethodAccess;
use PhpSchema\Traits\PublicProperties;

class Person extends Model
{
    use MethodAccess;
    use PublicProperties;

    protected static $schema = [
        "type" => "object",
        "properties" => [
            "firstName" => ["type" => "string"],
            "lastName" => ["type" => "string"],
            "age" => ["type" => "integer"],
            "address" => ['$ref' => "address.json"],
            "phoneNumber" => [
                "type" => "object",
                "properties" => [
                    "number" => ["type" => "string"]
                ]
            ]
        ],
        "required" => ["firstName", "lastName"]
    ];

    // All required properties must be passed into the constructor.  This ensures
    // the instance is in a valid state.  Parameter names must match the schema.
    public function __construct($firstName, $lastName)
    {
        parent::__construct($firstName, $lastName);
    }
}

$person = new Person("John", "Smith");

// while using PublicProperties trait
$person->firstName // "John"
$person->age = 42;
$person->age = "forty-two" // throws ValidationException


// while using MethodAccess trait
$person->firstName() // "John"
$person->age(42);
$person->age("forty-two"); // throws ValidationException


new Person("John", false); // throws ValidationException

```

### Converts to Array, Json, or StdClass
```php
<?php

$person = new Person("John", "Smith");

$person->toArray(); // ["firstName" => "John", "lastName" => "Smith"]
$person->toJson(); // {"firstName":"John","lastName":"Smith"}
$person->toObject(); // stdClass Object (...)
```

### Integrating with non-PhpSchema classes

While the root object must be an instance of PhpSchema, nested objects need only to implement the PhpSchema\Contracts\Arrayable interface.  Schema validation will be based on the returned associative array.

```php
<?php

namespace PhpSchema\Contracts;

interface Arrayable
{
    public function toArray(): array;
}
```

### Validates nested objects
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

// It validates nested objects of type StdClass
$person = new Person("John", "Smith");
$phoneNumber = new StdClass();
$phoneNumber->number = "843-867-5309";
$person->phoneNumber = $phoneNumber;

$person->toJson(); // {"firstName":"John","lastName":"Smith","phoneNumber":{"number":"843-867-5309"}}

```

For more examples, see the tests: `tests\ModelTest.php`