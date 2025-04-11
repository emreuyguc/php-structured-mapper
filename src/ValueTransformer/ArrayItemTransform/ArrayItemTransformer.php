<?php

declare(strict_types=1);

namespace Euu\StructuredMapper\ValueTransformer\ArrayItemTransform;

use Euu\StructuredMapper\Mapper\ObjectMapper\ObjectMapper;
use Euu\StructuredMapper\ValueTransformer\Base\Exception\ValueTransformationException;
use Euu\StructuredMapper\ValueTransformer\Base\TransformerAware;
use Euu\StructuredMapper\ValueTransformer\Base\TransformerAwareInterface;
use Euu\StructuredMapper\ValueTransformer\Base\ValueTransformerInterface;
use Euu\StructuredMapper\ValueTransformer\Base\ValueTransformerMeta;
use InvalidArgumentException;

class ArrayItemTransformer implements ValueTransformerInterface, TransformerAwareInterface
{
    use TransformerAware;

    public const USE_ADD_METHOD = 'use_add_method';
    public const CLEAR_METHOD = 'clear_method';
    public const CLEAR_EXPRESSION = 'clear_expression';

    public function transform(ValueTransformerMeta|ArrayItemTransform $transformerMeta, mixed $value, array &$mappingContext = []): array
    {
        if (!$transformerMeta instanceof ArrayItemTransform) {
            throw new InvalidArgumentException('Expected transformerMeta to be of type ArrayItemTransform.');
        }

        if (!is_array($value)) {
            throw new ValueTransformationException('Value must be an "array" !');
        }

        if (isset($transformerMeta->transformerContext[self::USE_ADD_METHOD])) {
            $mappingContext[ObjectMapper::ARRAY_ADD_METHOD] = $transformerMeta->transformerContext[self::USE_ADD_METHOD];
        }

        if (isset($transformerMeta->transformerContext[self::CLEAR_METHOD])) {
            $mappingContext[ObjectMapper::ARRAY_CLEAR_METHOD] = $transformerMeta->transformerContext[self::CLEAR_METHOD];
        }

        if (isset($transformerMeta->transformerContext[self::CLEAR_EXPRESSION])) {
            $mappingContext[ObjectMapper::ARRAY_CLEAR_EXPRESSION] = $transformerMeta->transformerContext[self::CLEAR_EXPRESSION];
        }

        $items = [];
        foreach ($value as $key => $item) {
            $items[] = $this->performTransformation($transformerMeta->itemTransformerMeta, $item, $mappingContext);
        }

        return $items;
    }
}
