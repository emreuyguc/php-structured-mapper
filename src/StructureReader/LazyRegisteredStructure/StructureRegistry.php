<?php

namespace Euu\StructuredMapper\StructureReader\LazyRegisteredStructure;

use Euu\StructuredMapper\Struct\MapStruct;
use Euu\StructuredMapper\Util\BaseCollection;

/**
 * @extends BaseCollection<string, MapStruct>
 */
class StructureRegistry extends BaseCollection
{
    /**
     * @param MapStruct[] $mapStructures
     */
    public function __construct(array $mapStructures = [])
    {
        parent::__construct(array_combine(
            array_map(fn (MapStruct $struct) => $this->getKey($struct->source, $struct->target), array_values($mapStructures)),
            array_values($mapStructures)
        ));
    }

    public function getKey(string $sourceClass, string $targetClass): string
    {
        return $sourceClass . '->' . $targetClass;
    }

    public function addStructure(string $sourceClass, string $targetClass, MapStruct $mapStruct): void
    {
        $this->set($this->getKey($sourceClass, $targetClass), $mapStruct);
    }

    public function getStructure(string $sourceClass, string $targetClass): ?MapStruct
    {
        return $this->offsetGet($this->getKey($sourceClass, $targetClass)) ?? null;
    }

    public function hasStructure(string $sourceClass, string $targetClass): bool
    {
        return $this->offsetExists($this->getKey($sourceClass, $targetClass));
    }

    public function removeStructure(string $sourceClass, string $targetClass): void
    {
        $this->offsetUnset($this->getKey($sourceClass, $targetClass));
    }
}
