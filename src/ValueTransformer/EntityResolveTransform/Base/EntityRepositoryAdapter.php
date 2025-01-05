<?php

namespace Euu\StructuredMapper\ValueTransformer\EntityResolveTransform\Base;

interface EntityRepositoryAdapter
{
    public function getRepository(string $entityName): ?object;
}
