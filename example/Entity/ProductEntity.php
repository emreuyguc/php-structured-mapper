<?php

namespace Euu\StructuredMapper\Example\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class ProductEntity
{
    public ?string $name = null;
    public ?string $description = null;

    public ?UnitType $unit = null;

    public ?string $model = null;
    public ?string $brand = null;

    public ?string $sku = null;
    public ?string $code = null;

    public ?OwnerEntity $owner = null;
    public ?StockEntity $stock = null;

    public ?string $stockWarehouse = null;

    public ?string $productCode = null;
    /**
     * @var SellerEntity[]
     */
    public ?array $sellers = null;

    /**
     * @var Collection<int, SubCategoryEntity>
     */
    public Collection $subCategories;

    public function __construct()
    {
        $this->subCategories = new ArrayCollection();
    }

    public function addSubCategory($subCategory): self
    {
        $this->subCategories[] = $subCategory;
        return $this;
    }

    public function addSeller($seller): self
    {
        $this->sellers[] = $seller;
        return $this;
    }

}
