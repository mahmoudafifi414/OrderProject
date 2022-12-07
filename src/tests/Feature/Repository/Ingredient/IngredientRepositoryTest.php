<?php

namespace Tests\Feature\Repository\Ingredient;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Package\Infrastructure\Mysql\Model\Eloquent\Ingredient;
use Package\Infrastructure\Mysql\Repository\Domain\Mysql\Ingredient\IngredientRepository;
use Tests\TestCase;

class IngredientRepositoryTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @return void
     */
    public function testUpdateOneOrMoreIngredient(): void
    {
        $ingredientRepository = new IngredientRepository();

        $productIngredients = [
            (object)['ingredientId' => 1, 'inStockQuantity' => 2, 'unit' => 'kg', 'productIngredientQuantity' => 1000],
            (object)['ingredientId' => 2, 'inStockQuantity' => 5, 'unit' => 'kg', 'productIngredientQuantity' => 500],
        ];
        $ingredientRepository->updateOneOrMoreIngredient($productIngredients);

        $ingredientAfterDeduction = Ingredient::whereIn('id',[1,2])->get();
        $this->assertEquals(1.0, $ingredientAfterDeduction[0]->in_stock_quantity);
        $this->assertEquals(4.5, $ingredientAfterDeduction[1]->in_stock_quantity);
    }

    /**
     * @return void
     */
    public function testMarkIngredientsAsNotified(): void
    {
        $ingredientRepository = new IngredientRepository();

        $ingredientRepository->markIngredientsAsNotified([1,2]);

        $ingredientAfterDeduction = Ingredient::whereIn('id',[1,2])->get();

        $this->assertEquals(1, $ingredientAfterDeduction[0]->notification_sent);
        $this->assertEquals(1, $ingredientAfterDeduction[1]->notification_sent);
    }
}
