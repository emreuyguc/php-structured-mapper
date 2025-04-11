<?php

declare(strict_types=1);

namespace Euu\StructuredMapper\Mapper\Base\Exception;

use Euu\StructuredMapper\Exception\ExceptionInterface;
use Euu\StructuredMapper\Exception\RuntimeException;
use Throwable;

class MapperNotFoundException extends RuntimeException implements ExceptionInterface
{
    public function __construct(string $mapperName, ?Throwable $previous = null)
    {
        $message = "Mapper '{$mapperName}' cant found in registered mappers.";
        parent::__construct($message, 0, $previous);
    }
}
