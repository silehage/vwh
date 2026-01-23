<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'title',
        'code',
        'slug',
        'category_id',
        'body',
    ];

    public function lines()
    {
        return $this->hasMany(ProductLine::class);
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->slug = Str::slug($model->title);
        });
    }
}
