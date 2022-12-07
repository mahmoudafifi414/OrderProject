<?php

namespace Package\Infrastructure\Mysql\Repository\Domain\Mysql\Ingredient;

use Illuminate\Support\Facades\DB;
use Package\Domain\Model\Ingredient\IIngredientRepository;
use Package\Infrastructure\Mysql\Model\Eloquent\Ingredient;

class IngredientRepository implements IIngredientRepository
{
    /**
     * this method update bulk data of ingredients data
     * @param array $productsIngredients
     * @return bool
     */
    public function updateOneOrMoreIngredient(array $productsIngredients): bool
    {
        return $this->makeBulkArrayOfIngredientsQuery($productsIngredients);
    }

    /**
     * @param array $ingredientsIds
     * @return bool
     */
    public function markIngredientsAsNotified(array $ingredientsIds): bool
    {
        return Ingredient::whereIn('id', $ingredientsIds)->update(['notification_sent' => 1]);
    }

    /**
     * @param array $productIngredients
     * @return bool
     */
    private function makeBulkArrayOfIngredientsQuery(array $productIngredients): bool
    {
        $ingredientIds = [];
        $cases = '';
        foreach ($productIngredients as $productIngredient) {
            $ingredientIds[] = $productIngredient->ingredientId;
            $newInStockQuantityValue = $productIngredient->inStockQuantity - ($productIngredient->unit == 'kg' ? $productIngredient->productIngredientQuantity / 1000 : $productIngredient->productIngredientQuantity);
            $cases .= sprintf(' WHEN id = %d THEN %.2f ', $productIngredient->ingredientId, $newInStockQuantityValue);
        }
        $caseQuery = sprintf("CASE %s END", $cases);

        return Ingredient::query()->whereIn('id', $ingredientIds)->update([
            'in_stock_quantity' => DB::raw($caseQuery)
        ]);
    }
}
