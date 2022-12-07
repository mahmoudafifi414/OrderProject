<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;
use Package\Infrastructure\Mysql\Model\Eloquent\ProductIngredient;

class ProductIngredientFactory extends Factory
{
    const PRODUCT_INGREDIENTS = [
        'burger' => [
            'productId' => 1,
            'ingredients' => [
                1,
                2,
                3
            ],
            'ingredient_quantity' => [
                150,
                30,
                20
            ]
        ],
    ];

    /**
     * @var int
     */
    private static int $index = 0;

    /**
     * @var string
     */
    protected $model = ProductIngredient::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */

    public function definition(): array
    {
        return [
             'product_id' => self::PRODUCT_INGREDIENTS['burger']['productId'],
             'ingredient_id' => self::PRODUCT_INGREDIENTS['burger']['ingredients'][self::$index],
             'ingredient_quantity' => self::PRODUCT_INGREDIENTS['burger']['ingredient_quantity'][self::$index++]
        ];
    }
}
