<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductLine;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class ProductService
{
   public function __construct(
      protected CategoryService $categoryService,
   ) {}
   public function index($requst)
   {
      return ProductLine::when($requst->product_id, function ($q) use ($requst) {
         $q->where('product_id', $requst->product_id);
      })->paginate($requst->per_page ?? 20);
   }
   public function list($requst)
   {
      return ProductLine::when($requst->product_id, function ($q) use ($requst) {
         $q->where('product_id', $requst->product_id);
      })->limit(500)->get();
   }
   public function productFilters()
   {
      return Cache::remember('product_options', now()->addMinutes(10), function () {
         return DB::table('products')->select('id as value', 'title as label')->get();
      });
   }
   public function store(array $data)
   {
      try {
         $category = null;
         if (isset($data['category_name']) && $data['category_name']) {
            $this->categoryService->store($data['category_name']);
         }

         $product = Product::firstOrCreate(
            ['code' => $data['product_code']],
            [
               'title' => $data['product_name'],
               'category_id' => $category?->id,
               'body' => $data['body'] ?? null,
            ]
         );

         ProductLine::updateOrCreate(
            ['sku_number' => $data['sku_number']],
            [
               'product_id' => $product->id,
               'title' => $data['product_detail'],
               'buy_price' => $data['buy_price'],
               'sell_price' => $data['sell_price'],
            ]
         );
      } catch (\Throwable $th) {
         throw $th;
      }
   }
}
