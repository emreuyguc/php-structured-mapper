<?php

namespace Euu\StructuredMapper\Struct\Attribute;

use Attribute;
use Euu\StructuredMapper\Mapper\ObjectMapper\ObjectMapper;
use Euu\StructuredMapper\Struct\MapStruct;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class MapTo extends MapStruct
{
    public function __construct(
        string $target,
        string $mapper = ObjectMapper::class,
        array  $mappings = [],
        array $mapperContext = []
    ) {
        parent::__construct('self', $target, $mapper, $mappings, $mapperContext);
    }
}
