<?php

namespace Package\Domain\Model\Ingredient;

use Illuminate\Support\Collection;

interface IIngredientRepository
{
    /**
     * @param array $productsIngredients
     * @return bool
     */
    public function updateOneOrMoreIngredient(array $productsIngredients): bool;
}
