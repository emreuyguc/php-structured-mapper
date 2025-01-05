<?php

namespace Euu\StructuredMapper\ValueTransformer;

use Euu\StructuredMapper\Util\BaseCollection;
use Euu\StructuredMapper\ValueTransformer\Base\ValueTransformerInterface;

/**
 * @extends BaseCollection<string, ValueTransformerInterface>
 */
class ValueTransformerRegistry extends BaseCollection
{
    /**
     * @param ValueTransformerInterface[] $transformers
     */
    public function __construct(array $transformers = [])
    {
        parent::__construct(
            array_combine(
                array_map(fn ($e) => $e::class, array_values($transformers)),
                $transformers
            )
        );
    }
}
