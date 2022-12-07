<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Package\Infrastructure\Mysql\Model\Eloquent\Ingredient;

class IngredientTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        Ingredient::factory()->count(3)->create();
    }
}
