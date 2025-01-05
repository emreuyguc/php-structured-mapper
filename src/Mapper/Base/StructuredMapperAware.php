<?php

namespace Euu\StructuredMapper\Mapper\Base;

use Euu\StructuredMapper\StructuredMapper;
use Euu\StructuredMapper\ValueTransformer\Base\Exception\ValueTransformationException;

trait StructuredMapperAware
{
    private ?StructuredMapper $structuredMapper = null;

    public function setStructuredMapper(StructuredMapper $structureReader): void
    {
        $this->structuredMapper = $structureReader;
    }

    public function performMapping(mixed $value, string $targetClass, array $mappingContext): object
    {
        if (!is_object($value)) {
            throw new ValueTransformationException('Value type must be an "object" !');
        }

        if (is_null($this->structuredMapper)) {
            throw new ValueTransformationException('Structured Mapper is required for with Mapper transformation!');
        }

        return $this->structuredMapper->map($value, $targetClass, $mappingContext);

    }
}
