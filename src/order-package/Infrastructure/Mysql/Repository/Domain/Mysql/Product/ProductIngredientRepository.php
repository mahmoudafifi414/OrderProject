<?php

namespace Package\Infrastructure\Mysql\Repository\Domain\Mysql\Product;

use Package\Domain\Model\Product\IProductIngredientRepository;
use Package\Infrastructure\Mysql\Model\Eloquent\ProductIngredient;

class ProductIngredientRepository implements IProductIngredientRepository
{
    /**
     * @param array $productIds
     * @return array
     */
    public function getProductsIngredients(array $productIds): array
    {
        return ProductIngredient::join('ingredients','product_ingredient.ingredient_id','ingredients.id')
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
