<?php

namespace Euu\StructuredMapper\ValueTransformer\WithMapperTransform;

use Euu\StructuredMapper\Mapper\Base\StructuredMapperAware;
use Euu\StructuredMapper\Mapper\Base\StructuredMapperAwareInterface;
use Euu\StructuredMapper\ValueTransformer\Base\ValueTransformerInterface;
use Euu\StructuredMapper\ValueTransformer\Base\ValueTransformerMeta;

class WithMapperTransformer implements ValueTransformerInterface, StructuredMapperAwareInterface
{
    use StructuredMapperAware;

    public function transform(ValueTransformerMeta|WithMapper $transformerMeta, mixed $value, array &$mappingContext = []): mixed
    {
        if (!$transformerMeta instanceof WithMapper) {
            throw new \InvalidArgumentException('Expected transformerMeta to be of type WithMapper.');
        }

        return $this->performMapping($value, $transformerMeta->targetClass, $mappingContext);
    }
}
