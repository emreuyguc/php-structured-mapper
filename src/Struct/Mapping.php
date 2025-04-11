<?php

declare(strict_types=1);

namespace Euu\StructuredMapper\Struct;

use Euu\StructuredMapper\ValueTransformer\Base\ValueTransformerMeta;

class Mapping
{
    public function __construct(
        public readonly array|string|null     $sourcePath,
        public readonly ?string               $targetPath = null,
        public readonly ?ValueTransformerMeta $transformerMeta = null,
        public array                          $mappingContext = []
    ) {
    }
}
