<?php

namespace Euu\StructuredMapper\Mapper;

use Euu\StructuredMapper\Mapper\Base\MapperInterface;
use Euu\StructuredMapper\Util\BaseCollection;

/**
 * @extends BaseCollection<string, MapperInterface>
 */
class MapperRegistry extends BaseCollection
{
    /**
     * @param MapperInterface[] $mappers
     */
    public function __construct(iterable $mappers = [])
    {
        parent::__construct(array_combine(
            array_map('get_class', array_values($mappers)),
            array_values($mappers)
        ));
    }
}
