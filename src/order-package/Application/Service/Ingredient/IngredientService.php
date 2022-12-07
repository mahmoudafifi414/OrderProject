<?php

namespace Package\Application\Service\Ingredient;

use Illuminate\Support\Collection;
use Package\Domain\Model\Ingredient\IIngredientRepository;
use Package\Domain\Model\Product\IProductIngredientRepository;

class IngredientService
{
    /**
     * @var IIngredientRepository $ingredientRepository;
     */
    private IIngredientRepository $ingredientRepository;

    /**
     * @param IIngredientRepository $ingredientRepository
     */
    public function __construct(
        IIngredientRepository $ingredientRepository,
    )
    {
          $this->ingredientRepository = $ingredientRepository;
    }

    /**
     * @param array $productsIngredients
     * @return bool
     */
    public function updateOneOrMoreIngredient(array $productsIngredients): bool
    {
        return $this->ingredientRepository->updateOneOrMoreIngredient($productsIngredients);
    }

    /**
     * function to check the ingredients below half.
     * @param array $productsIngredients
     * @return array
     */
    public function getIngredientsBelowHalfQuantity(array $productsIngredients): array
    {
        $ingredientsBelowHalfQuantity = [];
        foreach ($productsIngredients as $productIngredient){
            $newInStockQuantityValue = $productIngredient->inStockQuantity - ($productIngredient->unit == 'kg' ? $productIngredient->productIngredientQuantity / 1000 : $productIngredient->productIngredientQuantity);
            if (!$productIngredient->notificationSent && ($newInStockQuantityValue < ($productIngredient->startQuantity / 2))){
                $ingredientsBelowHalfQuantity[$productIngredient->ingredientId] = $productIngredient->ingredientName;
            }
        }

        return $ingredientsBelowHalfQuantity;
    }

    /**
     * @param array $ingredientsIds
     * @return bool
     */
    public function markIngredientsAsNotified(array $ingredientsIds): bool
    {
        return $this->ingredientRepository->markIngredientsAsNotified($ingredientsIds);
    }
}
