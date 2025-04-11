<?php

declare(strict_types=1);

namespace Euu\StructuredMapper\ValueTransformer\ImplodeTransform;

use Euu\StructuredMapper\ValueTransformer\Base\ValueTransformerMeta;

class ImplodeTransform extends ValueTransformerMeta
{
    public function __construct(
        public readonly string $separator = '',
        string                 $transformer = ImplodeTransformer::class,
        array                  $transformerContext = []
    ) {
        parent::__construct($transformer, $transformerContext);
    }
}
