<?php

namespace Euu\StructuredMapper\ValueTransformer\Base;

use Closure;
use Euu\StructuredMapper\ValueTransformer\Base\Exception\ValueTransformationException;

trait TransformerAware
{
    protected ?Closure $transformCallback = null;

    public function setTransformCallback(Closure $transformer): void
    {
        $this->transformCallback = $transformer;
    }

    public function performTransformation(ValueTransformerMeta $transformerMeta, mixed $sourceValue, array &$mappingContext): mixed
    {
        if (is_null($this->transformCallback)) {
            throw new ValueTransformationException(
                sprintf(
                    'Transform callback is not registered in class "%s". Please set the transform callback using the setTransformCallback method.',
                    get_class($this)
                )
            );
        }

        return ($this->transformCallback)($transformerMeta, $sourceValue, $mappingContext);
    }
}
