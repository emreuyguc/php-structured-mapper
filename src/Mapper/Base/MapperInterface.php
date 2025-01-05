<?php

namespace Euu\StructuredMapper\Mapper\Base;

use Euu\StructuredMapper\Struct\Mapping;

interface MapperInterface
{
    public function getName(): string;

    /**
     * @param Mapping[] $mappings
     */
    public function map(object $sourceObject, string $targetClass, array $mappings, array $mapperContext = []): object;
}
