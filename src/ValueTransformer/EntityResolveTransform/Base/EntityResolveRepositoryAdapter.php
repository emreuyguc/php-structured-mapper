<?php

declare(strict_types=1);

namespace Euu\StructuredMapper\ValueTransformer\EntityResolveTransform\Base;

interface EntityResolveRepositoryAdapter
{
    public function getRepository(string $entityName): ?object;
}
