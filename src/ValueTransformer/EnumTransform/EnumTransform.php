<?php

namespace Euu\StructuredMapper\ValueTransformer\EnumTransform;

use Euu\StructuredMapper\ValueTransformer\Base\ValueTransformerMeta;

class EnumTransform extends ValueTransformerMeta
{
    public function __construct(
        public readonly string $enumClass,
        public readonly bool   $fromCaseName = false,
        string                 $transformer = EnumTransformer::class,
        array                  $transformerContext = []
    ) {
        parent::__construct($transformer, $transformerContext);
    }
}
