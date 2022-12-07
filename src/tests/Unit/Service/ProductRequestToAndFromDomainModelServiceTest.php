<?php

namespace Tests\Unit\Service;

use Package\Application\Service\Product\ProductRequestToAndFromDomainModelService;
use Package\Domain\Model\Product\Product;
use Package\Domain\ValueObject\Product\ProductId;
use Package\Domain\ValueObject\Product\ProductQuantity;
use PHPUnit\Framework\TestCase;

class ProductRequestToAndFromDomainModelServiceTest extends TestCase
{
    /**
     * @return void
     */
    public function testToDomainModel(): void
    {
        $productRequestToAndFromDomainModelService = new ProductRequestToAndFromDomainModelService();
        $products = [
            [
                'product_id' => 1,
                'quantity' => 2
            ],
            [
                'product_id' => 2,
                'quantity' => 3
            ]
        ];

        $expectedResult = [
            new Product(
                ProductId::of(1),
                ProductQuantity::of(2)
            ),
            new Product(
                ProductId::of(2),
                ProductQuantity::of(3)
            )
        ];

        $this->assertEquals($expectedResult, $productRequestToAndFromDomainModelService->toDomainModel($products));
    }

    /**
     * @return void
     */
    public function testToProductIdsAndQuantitiesArray(): void
    {
        $productRequestToAndFromDomainModelService = new ProductRequestToAndFromDomainModelService();
        $productsDomainModels = [
            new Product(
                ProductId::of(1),
                ProductQuantity::of(2)
            ),
            new Product(
                ProductId::of(2),
                ProductQuantity::of(3)
            )
        ];

        $expectedResult = [
            '1' => [
                'productId' => 1,
                'quantity' => 2
            ],
            '2' => [
                'productId' => 2,
                'quantity' => 3
            ]
        ];

        $this->assertEquals($expectedResult, $productRequestToAndFromDomainModelService->toProductIdsAndQuantitiesArray($productsDomainModels)->all());
    }
}
