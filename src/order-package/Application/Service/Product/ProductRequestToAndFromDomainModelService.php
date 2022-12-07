<?php

namespace Package\Application\Service\Product;

use Illuminate\Support\Collection;
use Package\Domain\Model\Product\Product;
use Package\Domain\ValueObject\Product\ProductId;
use Package\Domain\ValueObject\Product\ProductQuantity;

class ProductRequestToAndFromDomainModelService
{
    /**
     * @param array $products
     * @return array
     */
    public function toDomainModel(array $products): array
    {
       $productsDomainModels = [];
       foreach ($products as $product) {
           $productsDomainModels[] = new Product(
              ProductId::of($product['product_id']),
              ProductQuantity::of($product['quantity'])
          );
       }

       return $productsDomainModels;
    }

    /**
     * @param array $productsDomainModels
     * @return Collection
     */
    public function toProductIdsAndQuantitiesArray(array $productsDomainModels): Collection
    {
        return collect($productsDomainModels)->mapWithKeys(function($productDomainModel){
            return [
                    $productDomainModel->getProductId()->getValue() => [
                    'productId' => $productDomainModel->getProductId()->getValue(),
                    'quantity' => $productDomainModel->getProductQuantity()->getValue()
                ]
            ];
        });
    }
}
