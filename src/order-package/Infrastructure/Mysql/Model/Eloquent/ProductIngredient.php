<?php

namespace Package\Infrastructure\Mysql\Model\Eloquent;

use Database\Factories\ProductIngredientFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductIngredient extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'product_ingredient';

    /**
     * @return ProductIngredientFactory
     */
    protected static function newFactory(): ProductIngredientFactory
    {
        return ProductIngredientFactory::new();
    }
}
