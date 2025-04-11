<?php

declare(strict_types=1);

namespace Euu\StructuredMapper\ValueTransformer\EntityResolveTransform;

use Euu\StructuredMapper\ValueTransformer\Base\ValueTransformerMeta;

class EntityResolve extends ValueTransformerMeta
{
    public function __construct(
        public readonly string $entity,
        public readonly string $repositoryMethod = 'find',
        public readonly ?array $findArguments = null,
        public readonly bool   $nullable = false,
        string                 $transformer = EntityResolveTransformer::class,
        array                  $transformerContext = []
    ) {
        parent::__construct($transformer, $transformerContext);
    }
}
