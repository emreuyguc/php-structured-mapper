<?php

declare(strict_types=1);

namespace Euu\StructuredMapper\StructureReader\LazyRegisteredStructure;

use Euu\StructuredMapper\Struct\MapStruct;
use Euu\StructuredMapper\StructureReader\Base\StructureReaderInterface;

class LazyRegisteredStructureReader implements StructureReaderInterface
{
    public function __construct(
        private readonly StructureReaderRegistry $structureReaderRegistry,
        private readonly StructureRegistry       $structureRegistry
    ) {
    }

    public function read(string $sourceClass, string $targetClass): ?MapStruct
    {
        if ($this->structureRegistry->hasStructure($sourceClass, $targetClass)) {
            return $this->structureRegistry->getStructure($sourceClass, $targetClass);
        }

        foreach ($this->structureReaderRegistry as $reader) {
            $mapStruct = $reader->read($sourceClass, $targetClass);
            if ($mapStruct !== null) {
                $this->structureRegistry->addStructure($sourceClass, $targetClass, $mapStruct);

                return $mapStruct;
            }
        }

        return null;
    }
}
