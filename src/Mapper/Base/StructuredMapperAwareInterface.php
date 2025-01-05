<?php

namespace Euu\StructuredMapper\Mapper\Base;

use Euu\StructuredMapper\StructuredMapper;

interface StructuredMapperAwareInterface
{
    public function setStructuredMapper(StructuredMapper $structuredMapper): void;
}
