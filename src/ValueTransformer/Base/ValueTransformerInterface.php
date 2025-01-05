<?php

namespace Euu\StructuredMapper\ValueTransformer\Base;

interface ValueTransformerInterface
{
    public function transform(ValueTransformerMeta $transformerMeta, mixed $value, array &$mappingContext = []): mixed;
}
