<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Package\Infrastructure\Mysql\Model\Eloquent\Product;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        Product::factory()->count(2)->create();
    }
}
