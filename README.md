# Php Structured Mapper

![PHP Version](https://img.shields.io/badge/php-%3E%3D8.2-blue)
![License](https://img.shields.io/badge/license-MIT-green)
![Author](https://img.shields.io/badge/author-emreuyguc-orange)

Structured Mapper is a simple and experimental data transformation library for PHP.

This project aims to simplify defining transformation rules using attributes and performing data type conversions. It also provides an extremely simple structure for ease of use.

## Features

- **Attribute-Based Mapping Definition:** Ability to define transformation rules using attributes.
- **mapFrom and mapTo Transformation Definitions:** Define transformation rules on either the source or target class.
- **Direct Property Definitions:** Define transformations directly on properties.
- **Context Passing and Usage:** Pass contextual information during mapping.
- **Value Transformers:** Perform type and data transformations using value transformer structures.
- **Important Transformers:** Predefined transformers for common use cases like Doctrine Entities, Enums, Array items, etc.
- **Structure Read and Storage Mechanism:** Extendable structure reader to read transformation definitions from different sources.
- **Custom Mapper Definitions:** Define custom transformation classes (see `MapperRegistry` and `MapperInterface`).
- **Array Item Transformations:** Transform array elements and process each element with a value transformer (see `ValueTransformer/ArrayItemTransform/ArrayItemTransformer.php`).
- **Sub-Object Transformations:** Define transformations for child objects (see `ValueTransformer/ObjectTransform/WithMapper.php`).

## Installation

Add Structured Mapper to your project using Composer:

```bash
composer require emreuyguc/structured-mapper
```

## Usage

For detailed examples, refer to the `example/ExampleFactory.php` file. Initialize the mapper according to your own usage scenarios.

## Examples

- `example/MapFromExample.php`
- `example/MapToExample.php`
- `example/Dto/`
- `example/Entity/`

## Limitations and Considerations

- **Object Constructor Issue:** Objects with constructors cannot be initialized during transformations. This area is open for improvement (Priority: High).
- **Reverse Transformation:** If `a -> b` transformations are defined, reverse (`b -> a`) is theoretically possible but not currently supported (Priority: Medium).
- **Type Conversion Mechanism:** There is no general mechanism for type conversions. Users must handle this manually or set the `type_enforcement` parameter to `false` for simple conversions (Priority: Low).
- **Property Naming:** Property names must be defined manually during mapping. An automatic name conversion mechanism can be added (Priority: Medium).
- **Cache Mechanism:** Currently, there is no caching mechanism. A suitable cache structure can be implemented (Priority: High).
- **Mapping Check:** A `canMap` method can be added for checking mappings (Priority: High).
- **Auto Sub-Type Mapping:** Automatically map child objects based on their types (Priority: Medium).
- **Custom Mapper Transformation Type Check:** Ensure that custom mapper definitions validate source and destination types. This check is currently missing (Priority: High).
- **Expression Usage in Entity Find Function:** Use expression language for custom parameters in entity transformations (Priority: Low).
- **Tests:** Write tests (Priority: High).

## License

This package is licensed under the [MIT License](LICENSE.md).
