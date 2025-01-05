<?php

namespace Euu\StructuredMapper\Example\Dto;

use Euu\StructuredMapper\Example\Entity\ProductEntity;
use Euu\StructuredMapper\Struct\Attribute\OnMapFrom;


class ProductOutputDto
{
	#[OnMapFrom(ProductEntity::class)]
	public ?string $name;

	#[OnMapFrom(ProductEntity::class,'code')]
	public string $description;
}
