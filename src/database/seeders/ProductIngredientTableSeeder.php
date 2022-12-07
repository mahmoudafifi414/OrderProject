<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Package\Infrastructure\Mysql\Model\Eloquent\ProductIngredient;

class ProductIngredientTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        ProductIngredient::factory()->count(3)->create();
    }
}
