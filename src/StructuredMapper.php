<?php

namespace Euu\StructuredMapper;

use Euu\StructuredMapper\Mapper\Base\Exception\MapperNotFoundException;
use Euu\StructuredMapper\Mapper\Base\StructuredMapperAwareInterface;
use Euu\StructuredMapper\Mapper\MapperRegistry;
use Euu\StructuredMapper\StructureReader\Base\StructureReaderInterface;
use Euu\StructuredMapper\StructureReader\Base\StructureReadException;

class StructuredMapper
{
    public function __construct(
        private readonly MapperRegistry           $mapperRegistry,
        private readonly StructureReaderInterface $structureReader,
        private readonly array                    $defaultMapperContext = []
    ) {
    }

    public function map(object $sourceObject, string $targetClass, array $mapperContext = []): object
    {
        $mapStruct = $this->structureReader->read($sourceObject::class, $targetClass);
        if (is_null($mapStruct)) {
            throw new StructureReadException($sourceObject::class, $targetClass);
        }

        $mapper = $this->mapperRegistry->get($mapStruct->mapper) ?? null;
        if (is_null($mapper)) {
            throw new MapperNotFoundException($mapStruct->mapper);
        }

        if ($mapper instanceof StructuredMapperAwareInterface) {
            $mapper->setStructuredMapper($this);
        }

        return $mapper->map(
            sourceObject: $sourceObject,
            targetClass: $targetClass,
            mappings: $mapStruct->mappings,
            mapperContext: array_merge($this->defaultMapperContext, $mapStruct->mapperContext, $mapperContext)
        );
    }
}
