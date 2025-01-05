<?php

namespace Euu\StructuredMapper\Example\Dto;

use Euu\StructuredMapper\Example\Entity\SellerEntity;
use Euu\StructuredMapper\Mapper\ObjectMapper\ObjectMapper;
use Euu\StructuredMapper\Struct\Attribute\MapTo;

#[MapTo(SellerEntity::class, mapperContext: [
    ObjectMapper::ALLOW_AUTO_MAPPING => true
])]
class SellerDto
{
    public string $market;
    public int $price;

}
