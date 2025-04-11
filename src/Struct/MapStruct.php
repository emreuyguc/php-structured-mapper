<?php

declare(strict_types=1);

namespace Euu\StructuredMapper\Struct;

use Euu\StructuredMapper\Mapper\ObjectMapper\ObjectMapper;

class MapStruct
{
    public function __construct(
        public readonly string $source,
        public readonly string $target,
        public readonly string $mapper = ObjectMapper::class,
        public readonly array  $mappings = [],
        public readonly array  $mapperContext = []
    ) {
    }
}
