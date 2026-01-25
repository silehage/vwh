<?php

namespace App\Services;

use App\Models\Stock;
use App\Models\ProductLine;
use App\Jobs\WebhookStockJob;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StockService
{

   public function index($request)
   {
      $stock_type = $request->stock_type ?? 'in';
      return Stock::withSum('items', 'quantity')->where('stock_type', $stock_type)->paginate(20);
   }

   /**
    * Store a newly created resource in storage.
    */
   public function store($request)
   {
      $stock = Stock::create([
         'stock_type' => $request->stock_type,
         'description' => $request->description,
         'created_by' => $request->user()->name
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

      if (in_array($stock->stock_type, [Stock::StockIn, Stock::TransferIn])) {
         foreach ($stock->items as $item) {
            DB::table('product_lines')->where('id', $item->product_line_id)->increment('total_stock', $item->quantity);
         }
      } else if (in_array($stock->stock_type, [Stock::StockOut, Stock::TransferOut])) {
         foreach ($stock->items as $item) {
            DB::table('product_lines')->where('id', $item->product_line_id)->decrement('total_stock', $item->quantity);
         }
      }

      WebhookStockJob::dispatch($stock);

      return $stock;
   }
   public function show($id)
   {
      return Stock::with('items.product')->find($id);
   }
   public function update($request, $id)
   {
      $stock = Stock::find($id);

      $stock->items()->delete();

      foreach ($request->stock_items as $item) {
         $stock->items()->create([
            'product_line_id' => $item['product_line_id'],
            'quantity' => $item['quantity']
         ]);
      }

      return $stock;
   }
   public function destroy($id)
   {
      $stock = Stock::find($id);
      $stock->items()->delete();
      $stock->delete();
      return 1;
   }
   public function incomingTransferStock($request)
   {
      DB::beginTransaction();
      try {
         $stock = Stock::where([
            'reference' => $request->reference,
            'stock_type' => Stock::TransferIn,
         ])->first();

         if ($stock) {
            $stock->update([
               'status' => $request->status,
               'description' => $request->description
            ]);
         } else {

            $stock = Stock::create([
               'status' => Stock::Processed,
               'stock_type' => Stock::TransferIn,
               'reference' => $request->reference,
               'description' => $request->description,
            ]);


            foreach ($request->stock_items as $item) {
               $productLine = ProductLine::where('sku_number', $item['sku_number'])->firstOrFail();
               $stock->items()->create([
                  'product_line_id' => $productLine->id,
                  'quantity' => $item['quantity']
               ]);
            }
         }

         // $stock = Stock::firstOrCreate([
         //    'reference' => $request->reference,
         //    'stock_type' => Stock::TransferIn,
         // ], [
         //    'status' => Stock::Processed,
         //    'stock_type' => Stock::TransferIn,
         //    'reference' => $request->reference,
         //    'description' => $request->description,
         // ]);

         // $stock->items()->delete();

         // foreach ($request->stock_items as $item) {
         //    $productLine = ProductLine::where('sku_number', $item['sku_number'])->firstOrFail();
         //    $stock->items()->create([
         //       'product_line_id' => $productLine->id,
         //       'quantity' => $item['quantity']
         //    ]);
         // }

         DB::commit();

         return $stock;
      } catch (\Throwable $th) {
         DB::rollback();

         Log::error($th);
         throw $th;
      }
   }
   public function getOptions()
   {
      return Stock::options();
   }
}
