<?php

namespace Package\Application\Service\Product;

use Illuminate\Support\Collection;
use Package\Domain\Model\Product\IProductIngredientRepository;

class ProductIngredientService
{
    /**
     * @var IProductIngredientRepository
     */
    private IProductIngredientRepository $productIngredientRepository;

    /**
     * @param IProductIngredientRepository $productIngredientRepository
     */
    public function __construct(
        IProductIngredientRepository $productIngredientRepository,
    )
    {
          $this->productIngredientRepository = $productIngredientRepository;
    }

    /**
     * @param Collection $productIdsAndQuantities
     * @return array
     */
    public function getProductsIngredients(Collection $productIdsAndQuantities): array
    {
        $productIds = $productIdsAndQuantities->pluck('productId')->all();
        //we use here productIngredientRepository instead of productRepository ,so we don't join with products table which we don't want it here.
        $productsIngredients = $this->productIngredientRepository->getProductsIngredients($productIds);
        return $this->groupProductsIngredientsQuantity($productsIngredients, $productIdsAndQuantities);
    }

    /**
     * @param array $productsIngredients
     * @return bool
     */
    public function isAnyIngredientOutOfStock(array $productsIngredients): bool
    {
        foreach ($productsIngredients as $productIngredient){
            $ingredientQuantity = $productIngredient->unit == 'kg' ? $productIngredient->inStockQuantity * 1000 : $productIngredient->inStockQuantity;
            if ($productIngredient->productIngredientQuantity > $ingredientQuantity){
                return true;
            }
        }

        return false;
    }

    /**
     * this method for grouping the quantity of productIngredient ,so it will be ready for check out of stock and to change our ingredient stock
     * @param array $productsIngredients
     * @param Collection $productIdsAndQuantities
     * @return array
     */
    private function groupProductsIngredientsQuantity(array $productsIngredients, Collection $productIdsAndQuantities): array
    {
        $productsIngredientsGroupedQuantity = [];
        foreach ($productsIngredients as $productIngredient){
            $productIngredient->productIngredientQuantity = $productIngredient->productIngredientQuantity * $productIdsAndQuantities[$productIngredient->productId]['quantity'];
            // if the ingredient is has another record with another product ,so sum all of its required quantity and put it in one array index.
            if (key_exists($productIngredient->ingredientId, $productsIngredientsGroupedQuantity)){
                $productsIngredientsGroupedQuantity[$productIngredient->ingredientId]->productIngredientQuantity =
                    $productsIngredientsGroupedQuantity[$productIngredient->ingredientId]->productIngredientQuantity + $productIngredient->productIngredientQuantity;
                continue;
            }
            unset($productIngredient->productId);
            $productsIngredientsGroupedQuantity[$productIngredient->ingredientId] = $productIngredient;
        }

        return $productsIngredientsGroupedQuantity;
    }
}
