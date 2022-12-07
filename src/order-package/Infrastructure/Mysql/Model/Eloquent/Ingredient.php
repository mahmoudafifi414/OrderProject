<?php

namespace Package\Infrastructure\Mysql\Model\Eloquent;

use Database\Factories\IngredientFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;

    /**
     * @return IngredientFactory
     */
    protected static function newFactory(): IngredientFactory
    {
        return IngredientFactory::new();
    }
}
