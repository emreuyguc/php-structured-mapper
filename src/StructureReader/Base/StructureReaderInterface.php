<?php

declare(strict_types=1);

namespace Euu\StructuredMapper\StructureReader\Base;

use Euu\StructuredMapper\Struct\MapStruct;

interface StructureReaderInterface
{
    public function read(string $sourceClass, string $targetClass): ?MapStruct;
}
