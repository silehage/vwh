<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = [
        'reference',
        'stock_type',
        'description',
        'status',
        'confirmed_by',
        'confirmed_at',
    ];

    const Pending = 'Pending';
    const Processed = 'Processed';
    const Shipped = 'Shipped';
    const Completed = 'Completed';

    const StockOut = 'out';
    const StockIn = 'in';
    const TransferIn = 'tin';
    const TransferOut = 'tout';

    public $appends = ['can_update', 'can_confirm'];

    public function scopeStockIn($query)
    {
        return $query->where('stock_type', self::StockIn);
    }
    public function scopeStockOut($query)
    {
        return $query->where('stock_type', self::StockOut);
    }
    public function scopeTransferIn($query)
    {
        return $query->where('stock_type', self::TransferIn);
    }
    public function scopeTransferOut($query)
    {
        return $query->where('stock_type', self::TransferOut);
    }

    public function items()
    {
        return $this->hasMany(StockItem::class);
    }
    public function canUpdate(): Attribute
    {
        $status = $this->status;
        return Attribute::make(
            get: fn() => in_array($status, [self::Pending]) ? true : false
        );
    }
    public function canConfirm(): Attribute
    {
        $status = false;
        if (is_null($this->confirmed_at)) {
            $status = true;
        }
        if (in_array($this->stock_type, [self::TransferIn, self::TransferOut]) && $this->status == self::Pending) {
            $status = false;
        }
        return Attribute::make(
            get: fn() => $status
        );
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            if (!$model->reference) {
                $lastId = Stock::max('id');
                $prefix = match ($model->stock_type) {
                    self::StockIn         => 'STI',
                    self::StockOut        => 'STO',
                    self::TransferIn      => 'TFI',
                    self::TransferOut     => 'TFO',
                    default     => 'TRX',
                };
                $model->reference = $prefix . now()->format('ym') . str_pad($lastId + 1, 6, '0', STR_PAD_LEFT);
            }
        });
    }
    public static function options()
    {
        return [
            ['label' => 'Stock In', 'value' => self::StockIn],
            ['label' => 'Stock Out', 'value' => self::StockOut],
            ['label' => 'Transfer In', 'value' => self::TransferIn],
            ['label' => 'Transfer Out', 'value' => self::TransferOut]
        ];
    }
}
