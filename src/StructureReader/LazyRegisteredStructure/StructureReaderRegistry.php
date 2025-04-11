<?php

declare(strict_types=1);

namespace Euu\StructuredMapper\StructureReader\LazyRegisteredStructure;

use Euu\StructuredMapper\StructureReader\Base\StructureReaderInterface;
use Euu\StructuredMapper\Util\BaseCollection;

/**
 * @extends BaseCollection<string,StructureReaderInterface>
 */
class StructureReaderRegistry extends BaseCollection
{
    /**
     * @param StructureReaderInterface[] $readers
     */
    public function __construct(array $readers = [])
    {
        parent::__construct(array_combine(
            array_map('get_class', array_values($readers)),
            array_values($readers)
        ));
    }
}
