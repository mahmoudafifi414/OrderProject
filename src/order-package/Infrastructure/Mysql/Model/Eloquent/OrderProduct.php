<?php

namespace Package\Infrastructure\Mysql\Model\Eloquent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'order_product';

    /**
     * @var string[]
     */
    protected $fillable = ['order_id', 'product_id', 'product_quantity'];
}
