<?php

declare(strict_types=1);

namespace Euu\StructuredMapper\ValueTransformer\Base;

abstract class ValueTransformerMeta
{
    public function __construct(
        public readonly string $transformer,
        public readonly array  $transformerContext = []
    ) {
    }
}
