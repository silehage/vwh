<?php

namespace App\Services;

use App\Models\Stock;
use Illuminate\Support\Facades\DB;

class StockService
{

   public function index($stock_type = 'in')
   {
      return Stock::withSum('items', 'quantity')->where('stock_type', $stock_type)->paginate(20);
   }

   /**
    * Store a newly created resource in storage.
    */
   public function store($request)
   {
      $stock = Stock::create([
         'stock_type' => $request->stock_type,
         'description' => $request->description
      ]);

      foreach ($request->stock_items as $item) {
         $stock->items()->create([
            'product_line_id' => $item['product_line_id'],
            'quantity' => $item['quantity']
         ]);
      }

      return $stock;
   }
   public function confirmed($request, $id)
   {

      $stock = Stock::find($id);

      $stock->update([
         'status' => Stock::Completed,
         'confirmed_at' => now(),
         'confirmed_by' => $request->user()->name
      ]);

      if ($stock->stock_type == Stock::StockIn) {
         foreach ($stock->items as $item) {
            DB::table('product_lines')->where('id', $item->product_line_id)->increment('total_stock', $item->quantity);
         }
      } else if ($stock->stock_type == Stock::StockOut) {
         foreach ($stock->items as $item) {
            DB::table('product_lines')->where('id', $item->product_line_id)->decrement('total_stock', $item->quantity);
         }
      }

      return $stock;
   }
   public function show($id)
   {
      return Stock::with('items.product')->find($id);
   }
   public function update($request, $id)
   {
      $stock = Stock::find();

      $stock->items()->delete();

      foreach ($request->stock_items as $item) {
         $stock->items()->create([
            'product_line_id' => $item['product_line_id'],
            'quantity' => $item['quantity']
         ]);
      }

      return $stock;
   }

   /**
    * Remove the specified resource from storage.
    */
   public function destroy($id)
   {
      $stock = Stock::find($id);
      $stock->items()->delete();
      $stock->delete();
      return 1;
   }
}
