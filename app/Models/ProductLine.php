<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductLine extends Model
{
    protected $fillable = [
        'title',
        'sku_number',
        'product_id',
        'buy_price',
        'sell_price',
        'total_Stock',
    ];
}
