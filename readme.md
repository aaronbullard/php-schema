# PhpSchema
[![Maintainability](https://api.codeclimate.com/v1/badges/1f20be630457fe6b3c57/maintainability)](https://codeclimate.com/github/aaronbullard/php-schema/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/1f20be630457fe6b3c57/test_coverage)](https://codeclimate.com/github/aaronbullard/php-schema/test_coverage)

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

### Testing

```bash
composer test
```

## Usage

PhpSchema provides a quick solution to roll objects from JSON Schema.

Objects self validate during construction and when mutated.

In keeping with DDD principles, PhpSchema objects should always be in a valid state.  Therefore all required properties must be passed through the constructor.  Any optional properties can be passed after instantiation.

Validation is built off of JsonSchema\Validator from justinrainbow/json-schema

## Examples

1) [PublicProperties](docs/public-properties.md) Trait
2) [MethodAccess](docs/method-access.md) Trait
3) Converting to [Array, Json, or StdClass](converter.md)
4) [Integrating](docs/non-phpschema.md) with non-PhpSchema classes

For more examples, see the tests: `tests\ModelTest.php`