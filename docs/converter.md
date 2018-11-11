# Converting to Array, Json, or StdClass

### Example

```php
<?php

$person = new Person("John", "Smith");

$person->toArray(); // ["firstName" => "John", "lastName" => "Smith"]
$person->toJson(); // {"firstName":"John","lastName":"Smith"}
$person->toObject(); // stdClass Object (...)
```

####
[Back](./../readme.md)