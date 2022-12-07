<?php

namespace Tests\Unit\Service;

use Package\Application\Service\Product\ProductIngredientService;
use Package\Infrastructure\Mysql\Repository\Domain\Mysql\Product\ProductIngredientRepository;
use PHPUnit\Framework\TestCase;
use ReflectionException;

class ProductIngredientServiceTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     * @throws ReflectionException
     */
    public function testGroupProductsIngredientsQuantityWithMoreThanSameIngredient(): void
    {
        $productIngredientService = new ProductIngredientService(
            new ProductIngredientRepository()
        );

        $productIngredients = [
            ['ingredientId' => 1, 'productId'=> 1, 'inStockQuantity' => 2, 'unit' => 'kg', 'productIngredientQuantity' => 1000],
            ['ingredientId' => 2, 'productId'=> 1, 'inStockQuantity' => 5, 'unit' => 'kg', 'productIngredientQuantity' => 500],
            ['ingredientId' => 3, 'productId'=> 1, 'inStockQuantity' => 10, 'unit' => 'kg', 'productIngredientQuantity' => 600],
            ['ingredientId' => 1, 'productId'=> 2, 'inStockQuantity' => 2, 'unit' => 'kg', 'productIngredientQuantity' => 500],
        ];

        $productIdsAndQuantities= collect([
          1 => [
              'productId' => 1,
              'quantity' => 2
          ],
          2 => [
              'productId' => 2,
              'quantity' => 2
            ]
        ]);

        $expected = [
          1 => ['ingredientId' => 1, 'inStockQuantity' => 2, 'unit' => 'kg', 'productIngredientQuantity' => 3000],
          2 => ['ingredientId' => 2, 'inStockQuantity' => 5, 'unit' => 'kg', 'productIngredientQuantity' => 1000],
          3 => ['ingredientId' => 3, 'inStockQuantity' => 10, 'unit' => 'kg', 'productIngredientQuantity' => 1200],
        ];

        $reflectionClass = new \ReflectionClass($productIngredientService);
        $method = $reflectionClass->getMethod('groupProductsIngredientsQuantity');
        $method->setAccessible(true);
        $result = $method->invokeArgs($productIngredientService,[$productIngredients, $productIdsAndQuantities]);
        $this->assertEquals($expected, $result);
    }

    /**
     * A basic unit test example.
     *
     * @return void
     * @throws ReflectionException
     */
    public function testGroupProductsIngredientsQuantityWithNotMoreThanSameIngredient(): void
    {
        $productIngredientService = new ProductIngredientService(
            new ProductIngredientRepository()
        );

        $productIngredients = [
            ['ingredientId' => 1, 'productId'=> 1, 'inStockQuantity' => 2, 'unit' => 'kg', 'productIngredientQuantity' => 1000],
            ['ingredientId' => 2, 'productId'=> 1, 'inStockQuantity' => 5, 'unit' => 'kg', 'productIngredientQuantity' => 500],
            ['ingredientId' => 3, 'productId'=> 1, 'inStockQuantity' => 10, 'unit' => 'kg', 'productIngredientQuantity' => 600],
        ];

        $productIdsAndQuantities= collect([
            1 => [
                'productId' => 1,
                'quantity' => 2
            ],
            2 => [
                'productId' => 2,
                'quantity' => 2
            ]
        ]);

        $expected = [
            1 => ['ingredientId' => 1, 'inStockQuantity' => 2, 'unit' => 'kg', 'productIngredientQuantity' => 2000],
            2 => ['ingredientId' => 2, 'inStockQuantity' => 5, 'unit' => 'kg', 'productIngredientQuantity' => 1000],
            3 => ['ingredientId' => 3, 'inStockQuantity' => 10, 'unit' => 'kg', 'productIngredientQuantity' => 1200],
        ];

        $reflectionClass = new \ReflectionClass($productIngredientService);
        $method = $reflectionClass->getMethod('groupProductsIngredientsQuantity');
        $method->setAccessible(true);
        $result = $method->invokeArgs($productIngredientService,[$productIngredients, $productIdsAndQuantities]);
        $this->assertEquals($expected, $result);
    }

    /**
     * @return void
     */
    public function testIsAnyIngredientOutOfStockIsFalse(): void
    {
        $productIngredientService = new ProductIngredientService(
            new ProductIngredientRepository()
        );

        $productIngredients = [
            ['ingredientId' => 1, 'productId'=> 1, 'inStockQuantity' => 2, 'unit' => 'kg', 'productIngredientQuantity' => 1000],
            ['ingredientId' => 2, 'productId'=> 1, 'inStockQuantity' => 5, 'unit' => 'kg', 'productIngredientQuantity' => 500],
            ['ingredientId' => 3, 'productId'=> 1, 'inStockQuantity' => 10, 'unit' => 'kg', 'productIngredientQuantity' => 600],
        ];

        $result = $productIngredientService->isAnyIngredientOutOfStock($productIngredients);

        $this->assertFalse($result);
    }

    /**
     * @return void
     */
    public function testIsAnyIngredientOutOfStockIsTrue(): void
    {
        $productIngredientService = new ProductIngredientService(
            new ProductIngredientRepository()
        );

        $productIngredients = [
            ['ingredientId' => 1, 'productId'=> 1, 'inStockQuantity' => 0.5, 'unit' => 'kg', 'productIngredientQuantity' => 1000],
            ['ingredientId' => 2, 'productId'=> 1, 'inStockQuantity' => 5, 'unit' => 'kg', 'productIngredientQuantity' => 500],
            ['ingredientId' => 3, 'productId'=> 1, 'inStockQuantity' => 10, 'unit' => 'kg', 'productIngredientQuantity' => 600],
        ];

        $result = $productIngredientService->isAnyIngredientOutOfStock($productIngredients);

        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testGetProductsIngredients(): void
    {
        $productIdsAndQuantities= collect([
            1 => [
                'productId' => 1,
                'quantity' => 2
            ],
            2 => [
                'productId' => 2,
                'quantity' => 2
            ]
        ]);

        $productIngredients = [
            ['ingredientId' => 1, 'productId'=> 1, 'inStockQuantity' => 2, 'unit' => 'kg', 'productIngredientQuantity' => 1000],
            ['ingredientId' => 2, 'productId'=> 1, 'inStockQuantity' => 5, 'unit' => 'kg', 'productIngredientQuantity' => 500],
            ['ingredientId' => 3, 'productId'=> 1, 'inStockQuantity' => 10, 'unit' => 'kg', 'productIngredientQuantity' => 600],
        ];
        $productIngredientRepository = $this->getMockBuilder(ProductIngredientRepository::class)
                                            ->onlyMethods(['getProductsIngredients'])
                                            ->disableOriginalConstructor()
                                            ->getMock();

        $productIngredientRepository->method('getProductsIngredients')->willReturn($productIngredients);

        $expected = [
            1 => ['ingredientId' => 1, 'inStockQuantity' => 2, 'unit' => 'kg', 'productIngredientQuantity' => 2000],
            2 => ['ingredientId' => 2, 'inStockQuantity' => 5, 'unit' => 'kg', 'productIngredientQuantity' => 1000],
            3 => ['ingredientId' => 3, 'inStockQuantity' => 10, 'unit' => 'kg', 'productIngredientQuantity' => 1200],
        ];

        $productIngredientService = new ProductIngredientService(
            $productIngredientRepository
        );

        $this->assertEquals($expected, $productIngredientService->getProductsIngredients($productIdsAndQuantities));
    }
}
