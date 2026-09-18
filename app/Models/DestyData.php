<?php

namespace App\Models;

use App\Helpers\Helper;
use App\Jobs\SeribugudangPushDestyDataJob;
use Illuminate\Database\Eloquent\Model;

class DestyData extends Model
{
    protected $fillable = [
        'orderId',
        'orderType',
        'orderCreateTime',
        'orderUpdateTime',
        'attempts',
        'reserved_at',
        'payload'
    ];

    protected $casts = [
        'payload' => 'array'
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->orderCreateTime = Helper::dateUtcToLocale($model->orderCreateTime);
            $model->orderUpdateTime = Helper::dateUtcToLocale($model->orderUpdateTime);
        });
        static::updating(function ($model) {
            $model->orderCreateTime = Helper::dateUtcToLocale($model->orderCreateTime);
            $model->orderUpdateTime = Helper::dateUtcToLocale($model->orderUpdateTime);
        });

        static::created(function($model) {
            SeribugudangPushDestyDataJob::dispatch($model);
        });
        static::updated(function($model) {
            SeribugudangPushDestyDataJob::dispatch($model);
        });
    }
}
