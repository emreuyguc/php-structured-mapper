<?php

declare(strict_types=1);

namespace Euu\StructuredMapper\Struct\Attribute;

use Attribute;
use Euu\StructuredMapper\Struct\Mapping;
use Euu\StructuredMapper\ValueTransformer\Base\ValueTransformerMeta;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class OnMapFrom extends Mapping
{
    public function __construct(
        public readonly string $sourceClass,
        ?string                $sourcePath = null,
        ?ValueTransformerMeta  $transformerMeta = null,
        array                  $mappingContext = []
    ) {
        parent::__construct($sourcePath, 'self', $transformerMeta, $mappingContext);
    }
}
