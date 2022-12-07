<?php

namespace Tests\Unit\Service;

use Package\Application\Service\Ingredient\IngredientService;
use Package\Infrastructure\Mysql\Repository\Domain\Mysql\Ingredient\IngredientRepository;
use PHPUnit\Framework\TestCase;

class IngredientServiceTest extends TestCase
{
    /**
     * @return void
     */
    public function testUpdateOneOrMoreIngredientReturnTrue(): void
    {
        $ingredientRepository = $this->getMockBuilder(IngredientRepository::class)
            ->onlyMethods(['updateOneOrMoreIngredient'])
            ->disableOriginalConstructor()
            ->getMock();
        $ingredientRepository->method('updateOneOrMoreIngredient')->willReturn(true);
        $ingredientService = new IngredientService($ingredientRepository);
        $this->assertTrue($ingredientService->updateOneOrMoreIngredient([]));
    }

    /**
     * @return void
     */
    public function testUpdateOneOrMoreIngredientReturnFalse(): void
    {
        $ingredientRepository = $this->getMockBuilder(IngredientRepository::class)
            ->onlyMethods(['updateOneOrMoreIngredient'])
            ->disableOriginalConstructor()
            ->getMock();
        $ingredientRepository->method('updateOneOrMoreIngredient')->willReturn(false);
        $ingredientService = new IngredientService($ingredientRepository);
        $this->assertFalse($ingredientService->updateOneOrMoreIngredient([]));
    }

    /**
     * @return void
     */
    public function testMarkIngredientsAsNotifiedReturnTrue(): void
    {
        $ingredientRepository = $this->getMockBuilder(IngredientRepository::class)
            ->onlyMethods(['markIngredientsAsNotified'])
            ->disableOriginalConstructor()
            ->getMock();
        $ingredientRepository->method('markIngredientsAsNotified')->willReturn(true);
        $ingredientService = new IngredientService($ingredientRepository);
        $this->assertTrue($ingredientService->markIngredientsAsNotified([1,2]));
    }

    /**
     * @return void
     */
    public function testMarkIngredientsAsNotifiedReturnFalse(): void
    {
        $ingredientRepository = $this->getMockBuilder(IngredientRepository::class)
            ->onlyMethods(['markIngredientsAsNotified'])
            ->disableOriginalConstructor()
            ->getMock();
        $ingredientRepository->method('markIngredientsAsNotified')->willReturn(false);
        $ingredientService = new IngredientService($ingredientRepository);
        $this->assertFalse($ingredientService->markIngredientsAsNotified([1,2]));
    }

    /**
     * @return void
     */
    public function testGetIngredientsBelowHalfQuantity(): void
    {
        $ingredientService = new IngredientService(
            new IngredientRepository()
        );

        $productIngredients = [
            ['ingredientId' => 1, 'notificationSent' => 0, 'ingredientName'=> 'Beef', 'startQuantity' => 2, 'inStockQuantity' => 2, 'unit' => 'kg', 'productIngredientQuantity' => 1500],
            ['ingredientId' => 2, 'notificationSent' => 0, 'ingredientName'=> 'Cheese', 'startQuantity' => 5, 'inStockQuantity' => 5, 'unit' => 'kg', 'productIngredientQuantity' => 500],
            ['ingredientId' => 3, 'notificationSent' => 0, 'ingredientName'=> 'Onion', 'startQuantity' => 10, 'inStockQuantity' => 10, 'unit' => 'kg', 'productIngredientQuantity' => 600],
        ];

        $expected = [
            1 => 'Beef'
        ];

        $this->assertEquals($expected, $ingredientService->getIngredientsBelowHalfQuantity($productIngredients));
    }

    /**
     * @return void
     */
    public function testGetIngredientsBelowHalfQuantityWithNotificationSent(): void
    {
        $ingredientService = new IngredientService(
            new IngredientRepository()
        );

        $productIngredients = [
            ['ingredientId' => 1, 'notificationSent' => 1, 'ingredientName'=> 'Beef', 'startQuantity' => 2, 'inStockQuantity' => 2, 'unit' => 'kg', 'productIngredientQuantity' => 1500],
            ['ingredientId' => 2, 'notificationSent' => 0, 'ingredientName'=> 'Cheese', 'startQuantity' => 5, 'inStockQuantity' => 5, 'unit' => 'kg', 'productIngredientQuantity' => 500],
            ['ingredientId' => 3, 'notificationSent' => 0, 'ingredientName'=> 'Onion', 'startQuantity' => 10, 'inStockQuantity' => 10, 'unit' => 'kg', 'productIngredientQuantity' => 600],
        ];

        $expected = [];

        $this->assertEquals($expected, $ingredientService->getIngredientsBelowHalfQuantity($productIngredients));
    }
}
