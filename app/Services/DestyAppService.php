<?php

namespace App\Services;

use App\Services\DestyClient;
use App\Services\ProductService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class DestyAppService
{
   protected $vendorName = 'DestyApp';
   public function __construct(
      protected DestyClient $destyClient,
      protected ProductService $productService
   ) {}

   public function getWarehouses(int $pageNumber = 1, int $pageSize = 10)
   {
      try {
         $payload = [
            'pageNumber' => $pageNumber,
            'pageSize' => $pageSize
         ];
         $data = $this->destyClient->getWarehouses($payload);
         return $data;
      } catch (\Throwable $th) {
         throw $th;
      }
   }

   public function getProductLists(int $pageNumber = 1, int $pageSize = 20)
   {

      try {
         $payload = [
            'pageNumber' => $pageNumber,
            'pageSize' => $pageSize
         ];

         $cacheKey = 'product-list-result_' . http_build_query($payload);
         if(Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
         }

         $data = $this->destyClient->getProductLists($payload);

         if(isset($data['results'])) {
            Cache::put($cacheKey, $data, now()->addHours(5));
         }

         return $data;
      } catch (\Throwable $th) {
         throw $th;
      }
   }
   public function printLabel($orderId)
   {

      try {
         $payload = [
            'orderId' => $orderId,
         ];
         $data = $this->destyClient->printLabel($payload);
         return $data;
      } catch (\Throwable $th) {
         throw $th;
      }
   }
   public function printLabelPDF($orderId, $format)
   {

      try {
         $payload = [
            'orderId' => $orderId,
            'format' => $format
         ];
         $data = $this->destyClient->printLabelPDF($payload);
         return $data;
      } catch (\Throwable $th) {
         throw $th;
      }
   }
   public function getSkuDetail($skuNumber = 'TVE016863')
   {

      try {
         // $skuNumber = 'TVE016863';
         $payload = [
            'skuNumber' => $skuNumber,
         ];
         $data = $this->destyClient->getSkuDetail($payload);
         return $data;
      } catch (\Throwable $th) {
         throw $th;
      }
   }
   public function getOrderLists(array $payload = [])
   {

      try {
         $defaultPayload = [
            'pageNumber' => 1,
            'pageSize' => 50
         ];

         $newPayload = array_merge($defaultPayload, $payload);

         $data = $this->destyClient->getOrderLists($newPayload);
         return $data;
      } catch (\Throwable $th) {
         throw $th;
      }
   }
   public function getOrderDetail($order_id)
   {
      try {
         $payload = [
            'orderId' => $order_id,
         ];
         $data = $this->destyClient->getOrderDetail($payload);
         return $data;
      } catch (\Throwable $th) {
         throw $th;
      }
   }
   public function getReturnOrderLists(int $pageNumber = 1, int $pageSize = 100, $subDays = 5)
   {

      try {
         $payload = [
            'startDate' => Carbon::now()->subDays($subDays)->getTimestampMs(),
            'pageNumber' => $pageNumber,
            'pageSize' => $pageSize
         ];
         $data = $this->destyClient->getReturnOrderLists($payload);
         return $data;
      } catch (\Throwable $th) {
         throw $th;
      }
   }
   public function getReturnOrderDetail($return_order_id)
   {
      try {
         $payload = [
            'returnOrderId' => $return_order_id,
         ];
         return $this->destyClient->getReturnOrderDetail($payload);
      } catch (\Throwable $th) {
         throw $th;
      }
   }
   public function inventorySyncStock(array $payload)
   {
      try {
         return $this->destyClient->inventorySyncStock($payload);
      } catch (\Throwable $th) {
         throw $th;
      }
   }

   public function getCnoteLabel($order)
   {

      try {
         $data = $this->printLabel($order->vendor_order_id);

         if ($data) {
            $order->cnote_url = $data;
         }
         $order->updated_at = now();
         $order->save();
         if ($data) {
            // CnoteSaveLocalFileJob::dispatch($order->fresh());
         }
         return true;
      } catch (\Throwable $th) {
         $msg = $th->getMessage();

         if (str_contains($msg, 'cancelled') || str_contains($msg, 'shipped')) {
            // DestyUpdateStatusJob::dispatch($order)->delay(15);
         }

         Log::error('error desty cnote', [
            'message' => $th->getMessage(),
         ]);

         return true;
      }
   }

   public function saveCnoteLabelPDF($order, $format = 'sellercenter')
   {

      try {
         $data = $this->printLabelPDF($order->vendor_order_id, $format);

         if (!$data) {
            return;
         }

         $path = '/files/pdf';
         $publicPath = public_path($path);

         if (!File::exists($publicPath)) {
            File::makeDirectory($publicPath, 0775, true, true);
         }

         $filename = uniqid() . '_' . $order->shipping_courier_code . '.pdf';
         $filepath = public_path("{$path}/{$filename}");

         file_put_contents($filepath, $data);

         $cnote = $order->localCnote()->create([
            'filename' => $filename,
            'filepath' => "{$path}/{$filename}",
            'disk' => 'public_upload',
            'visibility' => 'public'
         ]);

         $order->cnote_url = $cnote->src;
         $order->updated_at = now();
         $order->save();

         return $filename;
      } catch (\Throwable $th) {
         $msg = $th->getMessage();

         Log::error('error desty cnote PDF', [
            'message' => $msg,
         ]);

         return $msg;
      }
   }
}
