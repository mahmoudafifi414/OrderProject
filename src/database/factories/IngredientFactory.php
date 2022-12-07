<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Package\Infrastructure\Mysql\Model\Eloquent\Ingredient;

class IngredientFactory extends Factory
{
    const INGREDIENTS_NAMES = [
        'Beef',
        'Cheese',
        'Onion',
    ];

    const INGREDIENTS_QUANTITY = [
        20,
        5,
        1,
    ];

    /**
     * @var int
     */
    private static int $index = 0;

    /**
     * @var string
     */
    protected $model = Ingredient::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => self::INGREDIENTS_NAMES[self::$index],
            'start_quantity' => self::INGREDIENTS_QUANTITY[self::$index],
            'in_stock_quantity' => self::INGREDIENTS_QUANTITY[self::$index++]
        ];
    }
}
