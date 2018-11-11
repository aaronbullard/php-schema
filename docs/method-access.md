# Public Properties


### Define the class

```php
<?php

use PhpSchema\Model;
use PhpSchema\ValidationException;
use PhpSchema\Traits\MethodAccess;

class Person extends Model
{
    use MethodAccess;

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
        parent::__construct(compact('firstName', 'lastName'));
    }
}
```

### Example

```php
$person = new Person("John", "Smith");

// while using MethodAccess trait
$person->firstName() // "John"
$person->age(42);

try {
    $person->age("forty-two"); // throws ValidationException
} catch (ValidationException $e) {
    // $e->getMessage();
    // $e->getErrors();
}
```

####
[Back](./../readme.md)