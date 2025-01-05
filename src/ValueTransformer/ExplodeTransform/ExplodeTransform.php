<?php

namespace Euu\StructuredMapper\ValueTransformer\ExplodeTransform;

use Euu\StructuredMapper\ValueTransformer\Base\ValueTransformerMeta;

class ExplodeTransform extends ValueTransformerMeta
{
    public function __construct(
        public readonly string $separator,
        public readonly int    $index,
        public readonly ?int   $limit = null,
        string                 $transformer = ExplodeTransformer::class,
        array                  $transformerContext = []
    ) {
        parent::__construct($transformer, $transformerContext);
    }
}
