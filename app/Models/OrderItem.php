<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_line_id',
        'itemOrderId',
        'itemName',
        'imageUrl',
        'itemCode',
        'discountAmount',
        'originalPrice',
        'price',
        'sellPrice',
        'onHandStock',
        'quantity',
    ];
}
