<?php

namespace Tests\Feature\Repository\Product;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Package\Infrastructure\Mysql\Repository\Domain\Mysql\Product\ProductIngredientRepository;
use Tests\TestCase;

class ProductIngredientRepositoryTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @return void
     */
    public function testGetProductsIngredients(): void
    {
        $productIngredientRepository = new ProductIngredientRepository();
        $result = $productIngredientRepository->getProductsIngredients([1,2]);
        $expected =
            (object)[
                    'productId' => 1,
                    'productIngredientQuantity' => 150.0,
                    'ingredientId' => 1,
                    'ingredientName' => 'Beef',
                    'inStockQuantity' => 20.0,
                    'startQuantity' => 20.0,
                    'notificationSent' => 0,
                    'unit' => 'kg',
                ];
        $this->assertEquals($expected, $result[0]);
    }
}
