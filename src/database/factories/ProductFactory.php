<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Package\Infrastructure\Mysql\Model\Eloquent\Product;

class ProductFactory extends Factory
{
    const PRODUCTS_NAMES = [
        'Burger',
        'Pizza'
    ];

    /**
     * @var int
     */
    private static int $index = 0;

    /**
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => self::PRODUCTS_NAMES[self::$index++]
        ];
    }
}
