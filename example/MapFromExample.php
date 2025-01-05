<?php

use Euu\StructuredMapper\Example\Dto\ProductOutputDto;
use Euu\StructuredMapper\Example\Entity\ProductEntity;
use Euu\StructuredMapper\Example\ExampleFactory;

require_once '../vendor/autoload.php';

$entity = new ProductEntity();
$entity->name = 'çorap';
$entity->code = 'xyz123';

$mapper = ExampleFactory::initStructuredMapper();

dd($mapper->map($entity, ProductOutputDto::class));
