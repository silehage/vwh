<?php

namespace App\Services;

use App\Models\Category;

class CategoryService
{
   public function store($name)
   {
      $result = Category::firstOrCreate([
         'title' => $name,
      ]);

      return $result;
   }
}