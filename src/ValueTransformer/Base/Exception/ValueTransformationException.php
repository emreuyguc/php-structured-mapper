<?php

declare(strict_types=1);

namespace Euu\StructuredMapper\ValueTransformer\Base\Exception;

use Euu\StructuredMapper\Exception\ExceptionInterface;
use Euu\StructuredMapper\Exception\RuntimeException;

class ValueTransformationException extends RuntimeException implements ExceptionInterface
{
}
