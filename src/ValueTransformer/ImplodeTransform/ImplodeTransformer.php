<?php

declare(strict_types=1);

namespace Euu\StructuredMapper\ValueTransformer\ImplodeTransform;

use Euu\StructuredMapper\ValueTransformer\Base\Exception\ValueTransformationException;
use Euu\StructuredMapper\ValueTransformer\Base\ValueTransformerInterface;
use Euu\StructuredMapper\ValueTransformer\Base\ValueTransformerMeta;
use InvalidArgumentException;

class ImplodeTransformer implements ValueTransformerInterface
{
    public function transform(ValueTransformerMeta|ImplodeTransform $transformerMeta, mixed $value, array &$mappingContext = []): string
    {
        if (!$transformerMeta instanceof ImplodeTransform) {
            throw new InvalidArgumentException('Expected transformerMeta to be of type ImplodeTransform.');
        }

        if (!is_array($value)) {
            throw new ValueTransformationException('Value type must be an "array" !');
        }

        return implode($transformerMeta->separator, $value);
    }
}
