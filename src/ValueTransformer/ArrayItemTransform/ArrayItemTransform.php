<?php

declare(strict_types=1);

namespace Euu\StructuredMapper\ValueTransformer\ArrayItemTransform;

use Euu\StructuredMapper\ValueTransformer\Base\ValueTransformerMeta;

class ArrayItemTransform extends ValueTransformerMeta
{
    public function __construct(
        public readonly ValueTransformerMeta $itemTransformerMeta,
        string                               $transformer = ArrayItemTransformer::class,
        array                                $transformerContext = []
    ) {
        parent::__construct($transformer, $transformerContext);
    }
}
