<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = [
        'stock_type',
        'description',
        'status',
        'confirmed_by',
        'confirmed_at',
    ];

    const Pending = 'Pending';
    const Completed = 'Completed';

    const StockOut = 'out';
    const StockIn = 'in';

    public $appends = ['can_update', 'can_confirm'];

    public function items()
    {
        return $this->hasMany(StockItem::class);
    }
    public function canUpdate(): Attribute
    {
        $status = $this->status;
        return Attribute::make(
            get: fn () => in_array($status, [self::Pending]) ? true : false
        );
    }
    public function canConfirm(): Attribute
    {
        $confirmed = $this->confirmed_at;
        return Attribute::make(
            get: fn () => is_null($confirmed) ? true : false
        );
    }
}
