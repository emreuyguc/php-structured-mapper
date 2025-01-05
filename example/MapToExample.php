<?php

use Euu\StructuredMapper\Example\Dto\ProductDto;
use Euu\StructuredMapper\Example\Dto\SellerDto;
use Euu\StructuredMapper\Example\Dto\StockDto;
use Euu\StructuredMapper\Example\Entity\ProductEntity;
use Euu\StructuredMapper\Example\ExampleFactory;

require_once '../vendor/autoload.php';

$dto = new ProductDto();
$dto->name = 'çorap';
$dto->description = 'Comfortable cotton socks';
$dto->unit = 'kg';
$dto->brandModel = 'Nike-AirMax';
$dto->sku = '12345';
$dto->code = 'xyz123';
$dto->ownerName = 'John Doe';
$dto->ownerPhone = '1234567890';

$stockDto = new StockDto();
$stockDto->stockWarehouse = 'depo';
$stockDto->stockCount = 200;
$dto->stock = $stockDto;

$s1 = new SellerDto();
$s1->market = 'Amazon';
$s1->price = 50;

$s2 = new SellerDto();
$s2->market = 'Ebay';
$s2->price = 100;

$dto->sellers = [
	$s1, $s2
];


$dto->subCategoryIds = [1, 2, 3];

$mapper = ExampleFactory::initStructuredMapper();

dd($mapper->map($dto, ProductEntity::class));
