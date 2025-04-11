<?php

declare(strict_types=1);

namespace Euu\StructuredMapper\Util;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @template TKey of array-key
 * @template TValue
 *
 * @extends ArrayCollection<TKey, TValue>
 */
abstract class BaseCollection extends ArrayCollection
{
    public function __construct(iterable $items = [])
    {
        parent::__construct($items);
    }
}
