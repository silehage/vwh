<?php

use App\Helpers\ApiResponse;
use App\Models\Order;
use App\Services\DestyAppService;
use App\Services\DestyClient;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;


Route::get('getWarehouses', function (DestyAppService $destyAppService, Request $request) {
   $page = $request->pageNumber ?? 1;
   $limit = $request->pageSize ?? 10;
   $data = $destyAppService->getWarehouses($page, $limit);

   return ApiResponse::success($data);
});
Route::get('saveCnoteLabelPDF', function (DestyAppService $destyAppService, Request $request) {

   try {
      $request->validate([
         'order' => 'required'
      ]);
      $id = $request->order;
      $format = $request->format ?? 'sellercenter';

      $order = Order::where('order_ref', $id)
         ->orWhere('vendor_order_id', $id)
         ->orWhere('mp_ref', $id)
         ->orWhere('shipping_courier_code', $id)
         ->whereNotNull('shipping_courier_code')
         ->firstOrFail();

      $data = $destyAppService->saveCnoteLabelPDF($order, $format);

      return ApiResponse::success($data);
   } catch (\Throwable $th) {
      return ApiResponse::failed($th->getMessage());
   }
});

Route::get('getReturnOrderLists', function (DestyAppService $destyAppService, Request $request) {
   $data = $destyAppService->getReturnOrderLists($request->pageNumber ?? 1, $request->pageSize ?? 200, $request->subDays ?? 5);
   return $data;
   return ApiResponse::success($data);
});
Route::get('getReturnOrderDetail/{id}', function (DestyAppService $destyAppService, $id) {

   try {
      $data = $destyAppService->getReturnOrderDetail($id);

      return ApiResponse::success($data);
   } catch (\Throwable $th) {
      return ApiResponse::failed($th);
   }
});
Route::get('getProductLists', function (DestyAppService $destyAppService) {
   $data = $destyAppService->getProductLists(1, 200);

   return ApiResponse::success($data);
});
Route::post('inventorySyncStock', function (DestyAppService $destyAppService, Request $request) {

   try {
      $request->validate([
         'warehouseId' => ['required'],
         'skuNumber' => ['required'],
         'onHandStock' => ['required'],
      ]);

      $payload = [
         'warehouseId' => $request->warehouseId,
         'stocks' => [
            [
               'skuNumber' => $request->skuNumber,
               'onHandStock' => $request->onHandStock
            ]
         ]
      ];
      $data = $destyAppService->inventorySyncStock($payload);
      return ApiResponse::success($data);
   } catch (\Throwable $th) {
      return ApiResponse::failed($th);
   }
});

Route::get('getOrderDetail/{order_id}', function (DestyAppService $destyAppService, $order_id) {

   try {
      $data = $destyAppService->getOrderDetail($order_id);
      return ApiResponse::success($data);
   } catch (\Throwable $th) {
      return ApiResponse::failed($th);
   }
});
Route::get('getOrderLists', function (DestyClient $clientService, Request $request) {

   try {
      $request->validate([
         'startDate' => ['required', 'date'],
         'endDate' => ['required', 'date'],
      ]);
      $params = [
         'startDate' => Carbon::parse($request->startDate)->startOfDay()->getTimestampMs(),
         'endDate' => Carbon::parse($request->endDate)->endOfDay()->getTimestampMs(),
         'pageSize' => $request->pageSize ?? 100,
         'pageNumber' => $request->pageNumber ?? 1
      ];
      $data = $clientService->getOrderLists($params);
      return ApiResponse::success($data);
   } catch (\Throwable $th) {
      return ApiResponse::failed($th);
   }
});

Route::get('printLabel/{id}', function (DestyAppService $destyAppService, $id) {

   try {
      $data = $destyAppService->printLabel($id);
      return ApiResponse::success($data);
   } catch (\Throwable $th) {
      return ApiResponse::failed($th);
   }
});
