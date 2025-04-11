<?php

declare(strict_types=1);

namespace Euu\StructuredMapper\Mapper\Base;

use Euu\StructuredMapper\StructuredMapper;

interface StructuredMapperAwareInterface
{
    public function setStructuredMapper(StructuredMapper $structuredMapper): void;
}
