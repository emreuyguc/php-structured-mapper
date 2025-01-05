<?php

namespace Euu\StructuredMapper\Mapper\Base\Exception;

use Euu\StructuredMapper\Exception\ExceptionInterface;
use Euu\StructuredMapper\Exception\RuntimeException;

class PropertyMappingException extends RuntimeException implements ExceptionInterface
{
    public function __construct(string $propertyName, string $message, ?\Throwable $previous = null)
    {
        $message = "Property '{$propertyName}' mapping error: {$message}";
        parent::__construct($message, 0, $previous);
    }
}
