<?php

namespace Package\Infrastructure\Mysql\Repository\Domain\Mysql\Product;

use Illuminate\Support\Facades\DB;
use Package\Domain\Model\Product\IProductIngredientRepository;

class ProductIngredientRepository implements IProductIngredientRepository
{
    /**
     * @param array $productIds
     * @return array
     */
    public function getProductsIngredients(array $productIds): array
    {
        return DB::table('product_ingredient')
                ->join('ingredients','product_ingredient.ingredient_id','ingredients.id')
                ->whereIn('product_ingredient.product_id', $productIds)
                ->select([
                    'product_id AS productId',
                    'ingredient_quantity AS productIngredientQuantity',
                    'ingredients.id AS ingredientId',
                    'name AS ingredientName',
                    'start_quantity AS startQuantity',
                    'in_stock_quantity AS inStockQuantity',
                    'notification_sent AS notificationSent',
                    'unit'])
                ->get()
                ->toArray();
    }
}
