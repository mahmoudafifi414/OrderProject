<?php

namespace Package\Domain\Model\Product;

interface IProductIngredientRepository
{
    /**
     * @param array $productIds
     * @return array
     */
    public function getProductsIngredients(array $productIds): array;
}
