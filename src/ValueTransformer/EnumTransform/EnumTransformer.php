<?php

declare(strict_types=1);

namespace Euu\StructuredMapper\ValueTransformer\EnumTransform;

use Euu\StructuredMapper\ValueTransformer\Base\Exception\ValueTransformationException;
use Euu\StructuredMapper\ValueTransformer\Base\ValueTransformerInterface;
use Euu\StructuredMapper\ValueTransformer\Base\ValueTransformerMeta;
use ReflectionClass;
use InvalidArgumentException;
use UnitEnum;

class EnumTransformer implements ValueTransformerInterface
{
    public function transform(ValueTransformerMeta|EnumTransform $transformerMeta, mixed $value, array &$mappingContext = []): UnitEnum
    {
        if (!$transformerMeta instanceof EnumTransform) {
            throw new InvalidArgumentException('Expected transformerMeta to be of type EnumTransform.');
        }

        if (!$this->isEnumExists($transformerMeta->enumClass)) {
            throw new ValueTransformationException(sprintf('Enum class "%s" does not exist.', $transformerMeta->enumClass));
        }

        return $transformerMeta->fromCaseName ? $transformerMeta->enumClass::$value : $transformerMeta->enumClass::from($value);
    }

    private function isEnumExists(string $className): bool
    {
        if (!class_exists($className)) {
            return false;
        }

        $reflection = new ReflectionClass($className);

        return $reflection->isEnum();
    }
}
