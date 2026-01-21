<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $guarded = [];
    const MODULES = ['Product', 'User', 'Permission'];

    public static function createDefault()
    {
        $cruds = ['View','Create', 'Update', 'Delete'];

        foreach(self::MODULES as $module) {

            foreach($cruds as $c) {
                $name = $c . ' ' . $module;
                $slug = Str::slug($name);
                if(Permission::where('slug', $name)->count() == 0) {
                    Permission::create([
                        'name' => $name,
                        'slug' => $slug,
                        'tag' => $module
                    ]);
                }
            }
        }

    }

     protected static function booted()
    {
        static::creating(function($model) {
            if(!$model->slug) {
                $model->slug = Str::slug($model->name);
            }
        });

    }
}
