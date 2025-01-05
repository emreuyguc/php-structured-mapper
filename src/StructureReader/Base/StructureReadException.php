<?php

namespace Euu\StructuredMapper\StructureReader\Base;

use Euu\StructuredMapper\Exception\ExceptionInterface;
use Euu\StructuredMapper\Exception\RuntimeException;

class StructureReadException extends RuntimeException implements ExceptionInterface
{
    public function __construct(string $sourceClass, string $targetClass, ?\Throwable $previous = null)
    {
        $message = "Structure could not be read from {$sourceClass} to {$targetClass}. The target structure was not found.";
        parent::__construct($message, 0, $previous);
    }
}
