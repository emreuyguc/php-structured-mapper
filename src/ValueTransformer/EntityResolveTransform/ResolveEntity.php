<?php

namespace Euu\StructuredMapper\ValueTransformer\EntityResolveTransform;

use Euu\StructuredMapper\ValueTransformer\Base\ValueTransformerMeta;

class ResolveEntity extends ValueTransformerMeta
{
    public function __construct(
        public readonly string $entity,
        public readonly string $repositoryMethod = 'find',
        public readonly ?array $findArguments = null,
        public readonly bool   $nullable = false,
        string                 $transformer = ResolveEntityTransformer::class,
        array                  $transformerContext = []
    ) {
        parent::__construct($transformer, $transformerContext);
    }
}
