<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockItem extends Model
{
    protected $fillable = [
        'stock_id',
        'product_line_id',
        'quantity',
    ];

    public function product()
    {
        return $this->belongsTo(ProductLine::class, 'product_line_id');
    }
    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }
}
