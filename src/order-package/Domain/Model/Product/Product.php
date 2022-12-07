<?php

namespace Package\Domain\Model\Product;

use Package\Domain\Model\DomainModel;
use Package\Domain\ValueObject\Product\ProductId;
use Package\Domain\ValueObject\Product\ProductQuantity;

class Product extends DomainModel
{
    /**
     * @var ProductId
     */
    private ProductId $productId;
    /**
     * @var ProductQuantity
     */
    private ProductQuantity $productQuantity;

    /**
     * @param ProductId $productId
     * @param ProductQuantity $productQuantity
     */
    public function __construct(ProductId $productId, ProductQuantity $productQuantity)
    {
        $this->productId = $productId;
        $this->productQuantity = $productQuantity;
    }

    /**
     * @return ProductId
     */
    public function getProductId(): ProductId{
        return $this->productId;
    }

    /**
     * @return ProductQuantity
     */
    public function getProductQuantity(): ProductQuantity{
        return $this->productQuantity;
    }
}
