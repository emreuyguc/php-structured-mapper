<?php

declare(strict_types=1);

namespace Euu\StructuredMapper\Mapper\ObjectMapper;

use Euu\StructuredMapper\Exception\InvalidArgumentException;
use Euu\StructuredMapper\Exception\LogicException;
use Euu\StructuredMapper\Mapper\Base\MapperInterface;
use Euu\StructuredMapper\Mapper\Base\StructuredMapperAware;
use Euu\StructuredMapper\Mapper\Base\StructuredMapperAwareInterface;
use Euu\StructuredMapper\Struct\Mapping;
use Euu\StructuredMapper\ValueTransformer\Base\TransformerAwareInterface;
use Euu\StructuredMapper\ValueTransformer\Base\ValueTransformerMeta;
use Euu\StructuredMapper\ValueTransformer\ValueTransformerRegistry;
use ReflectionClass;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\PropertyInfo\PropertyInfoExtractorInterface;
use Symfony\Component\TypeInfo\Type\NullableType;
use Symfony\Component\TypeInfo\TypeIdentifier;
use Symfony\Component\TypeInfo\TypeResolver\TypeResolver;
use Symfony\Component\TypeInfo\TypeResolver\TypeResolverInterface;

class ObjectMapper implements MapperInterface, StructuredMapperAwareInterface
{
    use StructuredMapperAware;

    public const SKIP_NULL_VALUES = 'skip_null_values';
    public const TO_POPULATE = 'to_populate';
    public const ALLOW_AUTO_MAPPING = 'allow_auto_mapping';
    public const GROUPS = 'groups';

    public const AUTO_SUB_MAPPING = 'auto_sub_mapping';

    public const TYPE_ENFORCEMENT = 'type_enforcement';
    public const ARRAY_ADD_METHOD = 'array_add_method';
    public const ARRAY_CLEAR_METHOD = 'array_clear_method';
    public const ARRAY_CLEAR_EXPRESSION = 'array_clear_expression';

    private readonly TypeResolverInterface $typeResolver;

    private readonly ExpressionLanguage $expressionLanguage;

    public const DEFAULT_CONTEXT = [
        self::TO_POPULATE => null,
        self::GROUPS => [],
        self::ALLOW_AUTO_MAPPING => false,
        self::SKIP_NULL_VALUES => true,
        self::AUTO_SUB_MAPPING => false,
        self::TYPE_ENFORCEMENT => false,
        self::ARRAY_ADD_METHOD => null,
        self::ARRAY_CLEAR_METHOD => null,
        self::ARRAY_CLEAR_EXPRESSION => null,
    ];

    public function __construct(
        private readonly ValueTransformerRegistry       $transformerRegistry,
        private readonly PropertyInfoExtractorInterface $propertyInfoExtractor,
        private readonly PropertyAccessorInterface      $propertyAccessor,
        ?TypeResolverInterface                          $typeResolver = null
    ) {
        $this->typeResolver = $typeResolver ?? TypeResolver::create();
        $this->expressionLanguage = new ExpressionLanguage();
    }

    public function getName(): string
    {
        return self::class;
    }

    private function getTransformCallback(): callable
    {
        return function (ValueTransformerMeta $transformerMeta, mixed $sourceValue, &$mappingContext): mixed {
            $transformerInstance = $this->transformerRegistry->get($transformerMeta->transformer);
            if (is_null($transformerInstance)) {
                throw new InvalidArgumentException($transformerMeta->transformer . ' transformer not found');
            }

            if ($transformerInstance instanceof StructuredMapperAwareInterface) {
                $transformerInstance->setStructuredMapper($this->structuredMapper);
            }

            return $transformerInstance->transform($transformerMeta, $sourceValue, $mappingContext);
        };
    }

    public function map(object $sourceObject, string $targetClass, array $mappings, array $mapperContext = []): object
    {
        $mapperContext = array_merge(self::DEFAULT_CONTEXT, $mapperContext);

        $targetInstance = $mapperContext[self::TO_POPULATE] ?? new $targetClass();

        if ($mapperContext[self::ALLOW_AUTO_MAPPING] === true) {
            $autoMappings = $this->getAutoMappings($sourceObject, $targetClass);
        }
        $onStructureMappings = $this->getStructureMappings($mappings);

        /** @var Mapping[] $allMappings */
        $allMappings = $this->mergeMappings($autoMappings ?? [], $onStructureMappings);

        foreach ($allMappings as $mapping) {
            $sourcePath = $mapping->sourcePath;
            if (is_array($sourcePath) && $mapping->targetPath === null) {
                throw new InvalidArgumentException('When source path has a multiple source, mapping target path must be defined');
            }

            $targetPath = $mapping->targetPath ?? $sourcePath;

            $sourceValue = $this->getObjectValue($sourceObject, $sourcePath);
            if ($mapperContext[self::SKIP_NULL_VALUES] === true &&
                $sourceValue === null
            ) {
                continue;
            }

            $targetValue = $sourceValue;

            if (!is_null($mapping->transformerMeta)) {
                $transformerInstance = $this->transformerRegistry->get($mapping->transformerMeta->transformer);
                if (is_null($transformerInstance)) {
                    throw new InvalidArgumentException($mapping->transformerMeta->transformer . ' transformer not found');
                }

                $transformCallback = $this->getTransformCallback();

                if ($transformerInstance instanceof TransformerAwareInterface) {
                    $transformerInstance->setTransformCallback($transformCallback);
                }

                $targetValue = $transformCallback($mapping->transformerMeta, $sourceValue, $mapping->mappingContext);
                $transformerInstance = null;
            }

            $isTargetPathNested = $this->isPathNested($targetPath);
            $isTargetWriteable = fn () => $this->propertyAccessor->isWritable($targetInstance, $targetPath);

            if ($isTargetPathNested) {
                $parentTargetPath = explode('.', $targetPath, 2)[0];

                $parentTargetType = $this->propertyInfoExtractor->getType($targetClass, $parentTargetPath);

                if (true !== $parentTargetType?->isIdentifiedBy(TypeIdentifier::OBJECT)) {
                    throw new LogicException('When target path is nested , target path parent is should be object');
                }

                //todo burada parentTyargetType objectType olarak gelmeyebilir diğer içsel tiplerdede gelebilir...
                // collection<k,object>, list<object>
                if ($parentTargetType instanceof NullableType) {
                    $parentTargetType = $parentTargetType->getWrappedType();
                }
                $parentTargetClass = $parentTargetType->getClassName();

                if (!$isTargetWriteable()) {
                    $parentTargetInstance = $this->initializeObject($parentTargetClass);

                    $this->propertyAccessor->setValue($targetInstance, $parentTargetPath, $parentTargetInstance);
                }
            }

            $targetValueType = $targetValue ? $this->typeResolver->resolve(gettype($targetValue)) : null;

            if ($mapperContext[self::TYPE_ENFORCEMENT] === true) {
                if ($isTargetPathNested) {
                    $subTargetPath = explode('.', $targetPath, 2)[1];
                    $targetType = $this->propertyInfoExtractor->getType($parentTargetClass, $subTargetPath);
                } else {
                    $targetType = $this->propertyInfoExtractor->getType($targetClass, $targetPath);
                }


                if ($targetValueType && $targetType) {
                    if (!$targetType->isIdentifiedBy($targetValueType)) {
                        throw new LogicException(
                            (isset($transformerInstance) ? 'Transformed ' : 'Source class ') .
                            '[' . (is_array($sourcePath) ? implode(',', $sourcePath) : $sourcePath) .
                            "] property type({$targetValueType}) has not equal Target class [{$targetPath}] property type({$targetType})"
                        );
                    }
                }
            }

            if (!$isTargetWriteable()) {
                throw new LogicException('Target path [' . $targetPath . '] cant writable');
            }

            if (isset($mapping->mappingContext[self::ARRAY_ADD_METHOD])) {
                if (!$targetValueType->isIdentifiedBy(TypeIdentifier::ARRAY)) {
                    throw new LogicException('Target path [' . $targetPath . '] is not array');
                }

                $arrayAddMethod = $mapping->mappingContext[self::ARRAY_ADD_METHOD];
                if (!method_exists($targetInstance, $arrayAddMethod)) {
                    throw new LogicException('Array add method [' . $arrayAddMethod . '] not found in ' . $targetClass);
                }

                if (isset($mapping->mappingContext[self::ARRAY_CLEAR_METHOD])) {
                    $arrayClearMethod = $mapping->mappingContext[self::ARRAY_CLEAR_METHOD];
                    if (!isset($mapping->mappingContext[self::ARRAY_CLEAR_EXPRESSION])) {
                        throw new LogicException('When array clear method is defined, array_clear_expression must be defined');
                    }

                    $evaluation = $this->expressionLanguage->evaluate($mapping->mappingContext[self::ARRAY_CLEAR_EXPRESSION], [
                        'context' => $mapperContext,
                        'targetInstance' => $targetInstance,
                        'sourceInstance' => $sourceObject,
                    ]);

                    if ((bool) $evaluation) {
                        $this->expressionLanguage->evaluate(
                            'targetInstance.' . $arrayClearMethod,
                            [
                                'targetInstance' => $targetInstance,
                            ]
                        );
                    }
                }

                foreach ($targetValue as $item) {
                    $targetInstance->$arrayAddMethod($item);
                }

            } else {
                $this->propertyAccessor->setValue($targetInstance, $targetPath, $targetValue);
            }

        }

        return $targetInstance;
    }

    private function initializeObject(string $class, ...$params): object
    {
        return new $class(...$params);
    }

    private function getObjectValue(object $sourceObject, string|array $sourcePath): mixed
    {
        if (is_array($sourcePath)) {
            $values = [];
            foreach ($sourcePath as $path) {
                $values[$path] = $this->getObjectValue($sourceObject, $path);
            }

            return $values;
        }

        if (
            $this->isPathNested($sourcePath)
        ) {
            if (!$this->propertyAccessor->isReadable($sourceObject, $this->getParentPath($sourcePath))) {
                throw new LogicException($sourceObject::class . ' class property [' . $sourcePath . '] parent path cant readable !');
            }

            // TODO belki buraya parametre eklenebilir: alt erişimlerin parenti null olmamalı gibi
            if ($this->propertyAccessor->getValue($sourceObject, $this->getParentPath($sourcePath)) === null) {
                return null;
            }
        }

        if (!$this->propertyAccessor->isReadable($sourceObject, $sourcePath)) {
            throw new LogicException($sourceObject::class . ' class property [' . $sourcePath . '] cant readable!');
        }

        return $this->propertyAccessor->getValue($sourceObject, $sourcePath);
    }

    private function getParentPath(string $fullPath): string
    {
        return explode('.', $fullPath, 2)[0];
    }

    private function getAutoMappings(object $sourceObject, string $targetClass): array
    {
        $autoMappings = [];
        $classReflection = new ReflectionClass($sourceObject);
        foreach ($classReflection->getProperties() as $property) {
            if (property_exists($targetClass, $property->getName())) {
                $autoMappings[$property->getName()] = new Mapping($property->getName(), $property->getName());
            }
        }

        return $autoMappings;
    }

    /**
     * @param array<string, Mapping> $mappings
     */
    private function getStructureMappings(array $mappings): array
    {
        $structureMappings = [];
        /** @var Mapping $mapping */
        foreach ($mappings as $mapping) {
            $structureMappings[$mapping->targetPath ?? $mapping->sourcePath] = $mapping;
        }

        return $structureMappings;
    }

    private function mergeMappings(array ...$mappings): array
    {
        $merged = [];
        foreach ($mappings as $current) {
            foreach ($current as $key => $value) {
                if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
                    $merged[$key] = $this->mergeMappings($merged[$key], $value);
                } else {
                    $merged[$key] = $value;
                }
            }
        }

        return $merged;
    }

    private function isPathNested(string $path): bool
    {
        return str_contains($path, '.');
    }
}
