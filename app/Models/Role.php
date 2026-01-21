<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Role extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_role', 'role_id', 'permission_id');
    }
    public function users()
    {
        return $this->hasMany(User::class);
    }

    protected static function booted()
    {
        static::creating(function($model) {
            $model->slug = Str::slug($model->name);
        });

    }
}
