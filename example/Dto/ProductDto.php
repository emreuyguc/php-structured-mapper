<?php

namespace Euu\StructuredMapper\Example\Dto;

use Euu\StructuredMapper\Example\Entity\ProductEntity;
use Euu\StructuredMapper\Example\Entity\SellerEntity;
use Euu\StructuredMapper\Example\Entity\StockEntity;
use Euu\StructuredMapper\Example\Entity\SubCategoryEntity;
use Euu\StructuredMapper\Example\Entity\UnitType;
use Euu\StructuredMapper\Mapper\ObjectMapper\ObjectMapper;
use Euu\StructuredMapper\Struct\Attribute\MapTo;
use Euu\StructuredMapper\Struct\Attribute\OnMapTo;
use Euu\StructuredMapper\Struct\Mapping;
use Euu\StructuredMapper\ValueTransformer\ArrayItemTransform\ArrayItemTransform;
use Euu\StructuredMapper\ValueTransformer\ArrayItemTransform\ArrayItemTransformer;
use Euu\StructuredMapper\ValueTransformer\EntityResolveTransform\EntityResolve;
use Euu\StructuredMapper\ValueTransformer\EnumTransform\EnumTransform;
use Euu\StructuredMapper\ValueTransformer\ExplodeTransform\ExplodeTransform;
use Euu\StructuredMapper\ValueTransformer\ImplodeTransform\ImplodeTransform;
use Euu\StructuredMapper\ValueTransformer\WithMapperTransform\WithMapper;

#[MapTo(
    ProductEntity::class,
    mappings: [
        new Mapping(['sku', 'code'], 'productCode', new ImplodeTransform('-')),
        new Mapping('stock.stockWarehouse', 'stockWarehouse')
    ],
    mapperContext: [
        ObjectMapper::SKIP_NULL_VALUES => true,
        ObjectMapper::ALLOW_AUTO_MAPPING => false,
        ObjectMapper::TYPE_ENFORCEMENT => false
    ])]
class ProductDto
{
    //* Test: Auto transformation
    public string $name;
    public string $description;

    //* Test: Enum transformation
    #[OnMapTo(ProductEntity::class, targetPath: 'unit', transformerMeta: new EnumTransform(UnitType::class))]
    public string $unit;

    //* Test: Explode transform
    #[OnMapTo(ProductEntity::class, targetPath: 'model', transformerMeta: new ExplodeTransform('-', 1, 2))]
    #[OnMapTo(ProductEntity::class, targetPath: 'brand', transformerMeta: new ExplodeTransform('-', 0, 2))]
    public string $brandModel;

    //* Test: Auto mapping
    //* Test: Implode transformation
    public string $sku;
    public string $code;

    //* Test: Sub path transformation
    #[OnMapTo(ProductEntity::class, targetPath: 'owner.fullName')]
    public string $ownerName;

    //* Test: Sub path transformation with type enforcement
    #[OnMapTo(ProductEntity::class, targetPath: 'owner.phone')]
    public string $ownerPhone;

    //* Test sub object mapping
    #[OnMapTo(
        targetClass: ProductEntity::class,
        transformerMeta: new WithMapper(targetClass: StockEntity::class)
    )]
    public StockDto $stock;

    //* Test: sub object mapping with array items
    #[OnMapTo(ProductEntity::class, transformerMeta: new ArrayItemTransform(
        itemTransformerMeta: new WithMapper(targetClass: SellerEntity::class)
    ))]
    public array $sellers;

    //* Test: Array item & Find Entity Transform
    #[OnMapTo(
        ProductEntity::class,
        targetPath: 'subCategories',
        transformerMeta: new ArrayItemTransform(
            itemTransformerMeta: new EntityResolve(SubCategoryEntity::class),
            transformerContext: [
                ArrayItemTransformer::USE_ADD_METHOD => 'addSubCategory',
                ArrayItemTransformer::CLEAR_METHOD => 'subCategories.clear()',
                ArrayItemTransformer::CLEAR_EXPRESSION => "'update' in context['groups']"
            ]
        )
    )]
    public array $subCategoryIds;

}
