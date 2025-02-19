<?php

namespace Euu\StructuredMapper\Example;

use Euu\StructuredMapper\Example\Entity\OwnerEntity;
use Euu\StructuredMapper\Example\Entity\SellerEntity;
use Euu\StructuredMapper\Example\Entity\StockEntity;
use Euu\StructuredMapper\Example\Entity\SubCategoryEntity;
use Euu\StructuredMapper\Mapper\MapperRegistry;
use Euu\StructuredMapper\Mapper\ObjectMapper\ObjectMapper;
use Euu\StructuredMapper\StructuredMapper;
use Euu\StructuredMapper\StructureReader\AttributeStructure\AttributeStructureReader;
use Euu\StructuredMapper\StructureReader\LazyRegisteredStructure\LazyRegisteredStructureReader;
use Euu\StructuredMapper\StructureReader\LazyRegisteredStructure\StructureReaderRegistry;
use Euu\StructuredMapper\StructureReader\LazyRegisteredStructure\StructureRegistry;
use Euu\StructuredMapper\ValueTransformer\ArrayItemTransform\ArrayItemTransformer;
use Euu\StructuredMapper\ValueTransformer\EntityResolveTransform\Base\EntityResolveRepositoryAdapter;
use Euu\StructuredMapper\ValueTransformer\EntityResolveTransform\EntityResolveTransformer;
use Euu\StructuredMapper\ValueTransformer\EnumTransform\EnumTransformer;
use Euu\StructuredMapper\ValueTransformer\ExplodeTransform\ExplodeTransformer;
use Euu\StructuredMapper\ValueTransformer\ImplodeTransform\ImplodeTransformer;
use Euu\StructuredMapper\ValueTransformer\ValueTransformerRegistry;
use Euu\StructuredMapper\ValueTransformer\WithMapperTransform\WithMapperTransformer;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;

class ExampleFactory
{

	private static function initReflectionExtractor(): ReflectionExtractor
	{
		return new ReflectionExtractor();
	}

	private static function initPropertyInfoExtractor(ReflectionExtractor $reflectionExtractor): PropertyInfoExtractor
	{
		$listExtractors = [$reflectionExtractor];
		$typeExtractors = [$reflectionExtractor];
		$descriptionExtractors = [];
		$accessExtractors = [$reflectionExtractor];
		$propertyInitializableExtractors = [$reflectionExtractor];

		return new PropertyInfoExtractor(
			$listExtractors,
			$typeExtractors,
			$descriptionExtractors,
			$accessExtractors,
			$propertyInitializableExtractors
		);
	}

	public static function initFakeEntityManager(): EntityResolveRepositoryAdapter
	{
		return new class implements EntityResolveRepositoryAdapter {
			public function getRepository(string $entityName): ?object
			{
				$ownerRepository = new class {
					public function find(int $id): object
					{
						$e = (new OwnerEntity());
						$e->fullName = random_bytes(4);
						$e->phone = random_bytes(4);

						return $e;
					}
				};

				$subCategoryRepository = new class {
					public function find(int $id): object
					{
						$e = (new SubCategoryEntity());
						$e->name = random_bytes(4);
						$e->id = random_int(0, 100);

						return $e;
					}
				};

				$sellerRepository = new class {
					public function find(int $id): object
					{
						$e = (new SellerEntity());
						$e->price = random_int(0, 44);
						$e->market = random_bytes(4);

						return $e;
					}
				};

				$stockRepository = new class {
					public function find(int $id): object
					{
						$e = (new StockEntity());
						$e->stock = random_int(0, 44);
						$e->warehouse = random_bytes(4);

						return (new StockEntity());
					}
				};

				return match ($entityName) {
					StockEntity::class => $stockRepository,
					SellerEntity::class => $sellerRepository,
					OwnerEntity::class => $ownerRepository,
					SubCategoryEntity::class => $subCategoryRepository
				};
			}
		};

	}

	public static function initTransformers(): ValueTransformerRegistry
	{
		return new ValueTransformerRegistry([
			new WithMapperTransformer(),
			new ExplodeTransformer(),
			new ImplodeTransformer(),
			new ArrayItemTransformer(),
			new EnumTransformer(),
			new EntityResolveTransformer(self::initFakeEntityManager()),
		]);
	}

	public static function initDefaultObjectMapper(): ObjectMapper
	{
		$reflectionExtractor = self::initReflectionExtractor();

		return new ObjectMapper(
			transformerRegistry: self::initTransformers(),
			propertyInfoExtractor: self::initPropertyInfoExtractor($reflectionExtractor),
			propertyAccessor: new PropertyAccessor(
				readInfoExtractor: $reflectionExtractor,
				writeInfoExtractor: $reflectionExtractor
			)
		);
	}

	public static function initStructureReaders(): StructureReaderRegistry
	{
		return new StructureReaderRegistry([
			new AttributeStructureReader()
		]);
	}

	public static function initMappers(): MapperRegistry
	{
		return new MapperRegistry([
			self::initDefaultObjectMapper()
		]);
	}

	public static function initStructuredMapper(): StructuredMapper
	{
		$predefinedStructures = new StructureRegistry();

		$mainStructureReader = new LazyRegisteredStructureReader(
			structureReaderRegistry: self::initStructureReaders(),
			structureRegistry: $predefinedStructures
		);

		return new StructuredMapper(
			mapperRegistry: self::initMappers(),
			structureReader: $mainStructureReader,
			defaultMapperContext: [
				ObjectMapper::TYPE_ENFORCEMENT => false
			]
		);
	}
}


