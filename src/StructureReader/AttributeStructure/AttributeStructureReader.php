<?php

namespace Euu\StructuredMapper\StructureReader\AttributeStructure;

use Euu\StructuredMapper\Exception\InvalidArgumentException;
use Euu\StructuredMapper\Mapper\ObjectMapper\ObjectMapper;
use Euu\StructuredMapper\Struct\Attribute\MapFrom;
use Euu\StructuredMapper\Struct\Attribute\MapTo;
use Euu\StructuredMapper\Struct\Attribute\OnMapFrom;
use Euu\StructuredMapper\Struct\Attribute\OnMapTo;
use Euu\StructuredMapper\Struct\Mapping;
use Euu\StructuredMapper\Struct\MapStruct;
use Euu\StructuredMapper\StructureReader\Base\StructureReaderInterface;
use ReflectionClass;
use ReflectionException;

class AttributeStructureReader implements StructureReaderInterface
{
    /**
     * @throws ReflectionException
     */
    public function read(string $sourceClass, string $targetClass): ?MapStruct
    {
        $mapTo = $this->getMapTo($sourceClass, $targetClass);
        $mapFrom = $this->getMapFrom($sourceClass, $targetClass);
        if ($mapTo && $mapFrom) {
            if ($mapTo->mapper != $mapFrom->mapper) {
                throw new InvalidArgumentException("$sourceClass and $targetClass has MapTo and MapFrom structure but they dont have same mapper property");
            }
        }

        if (!$mapTo && !$mapFrom) {
            return null;
        }

        return new MapStruct(
            source: $sourceClass,
            target: $targetClass,
            mapper: $mapTo->mapper ?? $mapFrom->mapper,
            mappings: array_merge($mapTo->mappings ?? [], $mapFrom->mappings ?? []),
            mapperContext: array_merge($mapTo->mapperContext ?? [], $mapFrom->mapperContext ?? [])
        );
    }

    /**
     * @throws ReflectionException
     */
    private function getMapTo(string $sourceClass, string $targetClass): ?MapStruct
    {
        $reflection = new ReflectionClass($sourceClass);

        $propertyMappings = [];
        foreach ($reflection->getProperties() as $property) {
            $onMapAttributes = $property->getAttributes(OnMapTo::class);

            foreach ($onMapAttributes as $attribute) {
                /** @var OnMapTo $onMap */
                $onMap = $attribute->newInstance();

                $targetPath = $onMap->targetPath ?? $property->getName();
                if ($onMap->targetClass === $targetClass) {
                    $propertyMappings[$targetPath] = new Mapping(
                        $property->getName(),
                        $targetPath,
                        $onMap->transformerMeta,
                        $onMap->mappingContext
                    );
                }
            }
        }

        $mapToAttributes = $reflection->getAttributes(MapTo::class);

        $mapToInstance = null;
        foreach ($mapToAttributes as $mapToAttr) {
            /** @var MapTo $mapToInstance */
            $mapToInstance = $mapToAttr->newInstance();

            //todo duplicate check
            if ($mapToInstance->target !== $targetClass) {
                $mapToInstance = null;
            }
        }

        if (count($propertyMappings) || !is_null($mapToInstance)) {
            return new MapStruct(
                source: $sourceClass,
                target: $targetClass,
                mapper: $mapToInstance->mapper ?? ObjectMapper::class,
                mappings: array_merge($mapToInstance->mappings ?? [], $propertyMappings),
                mapperContext: $mapToInstance->mapperContext ?? []
            );
        }

        return null;
    }

    /**
     * @throws ReflectionException
     */
    private function getMapFrom(string $sourceClass, string $targetClass): ?MapStruct
    {
        $reflection = new ReflectionClass($targetClass);

        $propertyMappings = [];
        foreach ($reflection->getProperties() as $property) {
            $onMapAttributes = $property->getAttributes(OnMapFrom::class);

            foreach ($onMapAttributes as $attribute) {
                /** @var OnMapFrom $onMap */
                $onMap = $attribute->newInstance();

                if ($onMap->sourceClass === $sourceClass) {
                    $targetPath = $property->getName();
                    $sourcePath = $onMap->sourcePath ?? $property->getName();

                    $propertyMappings[$targetPath] = new Mapping(
                        $sourcePath,
                        $targetPath,
                        $onMap->transformerMeta,
                        $onMap->mappingContext
                    );
                }
            }
        }

        $mapFromAttributes = $reflection->getAttributes(MapFrom::class);

        $mapFromInstance = null;
        foreach ($mapFromAttributes as $mapToAttr) {
            /** @var MapFrom $mapFromInstance */
            $mapFromInstance = $mapToAttr->newInstance();

            //todo duplicate check
            if ($mapFromInstance->source !== $sourceClass) {
                $mapFromInstance = null;
            }
        }

        if (count($propertyMappings) || !is_null($mapFromInstance)) {
            return new MapStruct(
                source: $sourceClass,
                target: $targetClass,
                mapper: $mapFromInstance->mapper ?? ObjectMapper::class,
                mappings: array_merge($mapFromInstance->mappings ?? [], $propertyMappings),
                mapperContext: $mapFromInstance->mapperContext ?? []
            );
        }

        return null;
    }
}
