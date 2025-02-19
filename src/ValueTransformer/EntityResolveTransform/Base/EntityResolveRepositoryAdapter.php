<?php

namespace Euu\StructuredMapper\ValueTransformer\EntityResolveTransform\Base;

interface EntityResolveRepositoryAdapter
{
    public function getRepository(string $entityName): ?object;
}
