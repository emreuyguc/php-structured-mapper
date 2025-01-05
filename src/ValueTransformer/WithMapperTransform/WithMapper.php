<?php

namespace Euu\StructuredMapper\ValueTransformer\WithMapperTransform;

use Euu\StructuredMapper\ValueTransformer\Base\ValueTransformerMeta;

class WithMapper extends ValueTransformerMeta
{
    //todo manuel mappings eklenebilir
    public function __construct(
        public readonly string $targetClass,
        string $transformer = WithMapperTransformer::class,
        array $transformerContext = []
    ) {
        parent::__construct($transformer, $transformerContext);
    }
}
