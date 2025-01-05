<?php

namespace Euu\StructuredMapper\ValueTransformer\Base;

use Closure;

interface TransformerAwareInterface
{
    public function setTransformCallback(Closure $transformer): void;

    public function performTransformation(ValueTransformerMeta $transformerMeta, mixed $sourceValue, array &$mappingContext): mixed;
}
