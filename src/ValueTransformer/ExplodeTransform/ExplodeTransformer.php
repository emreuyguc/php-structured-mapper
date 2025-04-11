<?php

declare(strict_types=1);

namespace Euu\StructuredMapper\ValueTransformer\ExplodeTransform;

use Euu\StructuredMapper\ValueTransformer\Base\Exception\ValueTransformationException;
use Euu\StructuredMapper\ValueTransformer\Base\ValueTransformerInterface;
use Euu\StructuredMapper\ValueTransformer\Base\ValueTransformerMeta;
use InvalidArgumentException;

class ExplodeTransformer implements ValueTransformerInterface
{
    public function transform(ValueTransformerMeta|ExplodeTransform $transformerMeta, mixed $value, array &$mappingContext = []): string
    {
        if (!$transformerMeta instanceof ExplodeTransform) {
            throw new InvalidArgumentException('Expected transformerMeta to be of type ExplodeTransform.');
        }

        if (!is_string($value)) {
            throw new ValueTransformationException('Value type must be an "string" !');
        }

        return explode($transformerMeta->separator, $value, $transformerMeta->limit)[$transformerMeta->index];
    }
}
