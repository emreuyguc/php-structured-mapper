<?php

namespace Euu\StructuredMapper\Example\Dto;

use Euu\StructuredMapper\Example\Entity\StockEntity;
use Euu\StructuredMapper\Struct\Attribute\OnMapTo;


class StockDto
{
    #[OnMapTo(StockEntity::class, targetPath: 'stock')]
    public ?int $stockCount = null;

    #[OnMapTo(StockEntity::class, targetPath: 'warehouse')]
    public ?string $stockWarehouse = null;
}
