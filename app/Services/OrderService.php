<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Store;
use App\Helpers\Helper;
use App\Models\ProductLine;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderService
{
   public function process($data)
   {
      DB::beginTransaction();
      try {
         $orderStatusList = null;
         $subOrderStatusList = null;
         $platformOrderStatusList = null;

         if (is_array($data['orderStatusList'])) {
            $orderStatusList = $data['orderStatusList'][0];
         } else if (is_string($data['orderStatusList'])) {
            $orderStatusList = $data['orderStatusList'];
         }
         if (is_array($data['subOrderStatusList'])) {
            $subOrderStatusList = $data['subOrderStatusList'][0];
         } else if (is_string($data['subOrderStatusList'])) {
            $subOrderStatusList = $data['subOrderStatusList'];
         }

         if (is_array($data['platformOrderStatusList'])) {
            $platformOrderStatusList = $data['platformOrderStatusList'][0];
         } else if (is_string($data['platformOrderStatusList'])) {
            $platformOrderStatusList = $data['platformOrderStatusList'];
         }

         // START
         $order = Order::where('orderId', $data['orderId'])->first();

         if ($data['storeId'] && $data['storeName']) {

            Store::updateOrCreate([
               'store_id' => $data['storeId'],
            ], [
               'store_id' => $data['storeId'],
               'store_name' => $data['storeName'],
               'platform' => $data['platform'],
               'platform_name' => $data['platformName'],
            ]);
         }

         $destyItem = $data['itemList'][0];

         $deliveryDeadline =  null;
         $trackingNumber = null;
         $orderCreateTime = Helper::date_from_utc_to_locale($data['orderCreateTime']);

         $orderItems = [];

         foreach ($data['itemList'] as $item) {
            $productLine = null;
            $skuNumber = $item['itemCode'] ?? $item['itemExternalCode'];

            if ($skuNumber) {
               $productLine = ProductLine::where('sku_number', $skuNumber)->first();
            }

            if (!$trackingNumber) {
               $trackingNumber = $item['shippingDetail']['trackingNumber'] ?? NULL;
            }

            if (!$deliveryDeadline) {
               $dl = $item['shippingDetail']['deliveryDeadline'] ?? NULL;
               $deliveryDeadline =  $dl ? Helper::date_from_utc_to_locale($dl) : NULL;
            }

            $orderItems[] = [
               'product_line_id' => $productLine ? $productLine->id : null,
               'itemOrderId' => $item['itemOrderId'],
               'itemName' => $item['itemName'],
               'skuNumber' => $skuNumber,
               'discountAmount' => $item['discountAmount'],
               'originalPrice' => $item['originalPrice'],
               'price' => $item['price'],
               'sellPrice' => $item['sellPrice'],
               'onHandStock' => $item['onHandStock'] ?? 0,
               'quantity' => $item['quantity'],
            ];
         }

         if ($order) {

            $updatePayload = [
               'createTime' => $orderCreateTime,
               'cancelBy' => $data['cancelBy'] ?? null,
               'hasPaid' => $data['hasPaid'],
               'paymentMethod' => $data['paymentMethod'],
               'logisticStatus' => $data['logisticStatus'],
               'orderStatusList' => $orderStatusList,
               'subOrderStatusList' => $subOrderStatusList,
               'platformOrderStatusList' => $platformOrderStatusList,
               'bookingSn' => $data['bookingSn'],
            ];

            if ($deliveryDeadline) {
               $updatePayload['deliveryDeadline'] = $deliveryDeadline;
            }

            if ($trackingNumber) {
               $updatePayload['trackingNumber'] = $trackingNumber;
            }
            if (!$order->courier) {
               $updatePayload['courier'] = $destyItem['shippingDetail']['courier'] ?? NULL;
            }
            if (!$order->shippingAddress) {
               $updatePayload['shippingAddress'] = $destyItem['shippingDetail']['shippingAddress'];
               $updatePayload['shippingArea'] = $destyItem['shippingDetail']['shippingArea'];
               $updatePayload['shippingCity'] = $destyItem['shippingDetail']['shippingCity'];
               $updatePayload['shippingCost'] = $destyItem['shippingDetail']['shippingCost'];
               $updatePayload['shippingFullName'] = $destyItem['shippingDetail']['shippingFullName'];
               $updatePayload['shippingPhone'] = $destyItem['shippingDetail']['shippingPhone'];
               $updatePayload['shippingProvince'] = $destyItem['shippingDetail']['shippingProvince'];
               $updatePayload['shippingPostCode'] = $destyItem['shippingDetail']['shippingPostCode'];
            }

            $order->update($updatePayload);
         } else {

            $orderPayload = [
               'buyerNotes' => $data['buyerNotes'] ?? null,
               'cancelBy' => $data['cancelBy'] ?? null,
               'codOrder' => $data['codOrder'],
               'createTime' => $orderCreateTime,
               'customerEmail' => $data['customerInfo']['email'] ?? null,
               'customerName' => $data['customerInfo']['name'],
               'customerPhone' => $data['customerInfo']['phone'] ?? null,
               'discount' => $data['discount'],
               'hasPaid' => $data['hasPaid'],
               'includeTax' => $data['includeTax'],
               'insuranceCost' => $data['insuranceCost'],
               'logisticStatus' => $data['logisticStatus'],
               'orderId' => $data['orderId'],
               'uid' => $data['orderId'],
               'orderSn' => $data['orderSn'],
               'bookingSn' => $data['bookingSn'],
               'paymentMethod' => $data['paymentMethod'],
               'orderStatusList' => $orderStatusList,
               'subOrderStatusList' => $subOrderStatusList,
               'platformOrderStatusList' => $platformOrderStatusList,
               'platform' => $data['platform'],
               'platformName' => $data['platformName'],
               'preOrder' => $data['preOrder'],
               'storeId' => $data['storeId'],
               'storeName' => $data['storeName'],
               'subTotal' => $data['subTotal'],
               'tax' => $data['tax'],
               'totalPrice' => $data['totalPrice'],
               'totalWeight' => $data['totalWeight'],
               'trackingNumber' => $trackingNumber,
               'deliveryDeadline' => $deliveryDeadline,
               'courier' => $destyItem['shippingDetail']['courier'] ?? NULL,
               'shippingAddress' => $destyItem['shippingDetail']['shippingAddress'],
               'shippingArea' => $destyItem['shippingDetail']['shippingArea'],
               'shippingCity' => $destyItem['shippingDetail']['shippingCity'],
               'shippingCost' => $destyItem['shippingDetail']['shippingCost'],
               'shippingFullName' => $destyItem['shippingDetail']['shippingFullName'],
               'shippingPhone' => $destyItem['shippingDetail']['shippingPhone'],
               'shippingProvince' => $destyItem['shippingDetail']['shippingProvince'],
               'shippingPostCode' => $destyItem['shippingDetail']['shippingPostCode'],
            ];

            $order = Order::create($orderPayload);

            $order->items()->createMany($orderItems);
         }
         DB::commit();
      } catch (\Throwable $th) {
         DB::rollBack();
         Log::error($th->getMessage());
      }
   }

   public function index($request)
   {
      return Order::when($request->search, function ($q) use ($request) {
         $key = $request->search;
         $q->where('orderSn', $key)
            ->orWhere('bookingSn', $key)
            ->orWhere('orderId', $key)
            ->orWhere('storeName', $key)
            ->orWhere('customerEmail', $key)
            ->orWhere('customerName', $key)
            ->orWhere('customerPhone', $key)
            ->orWhere('platform', $key)
            ->orWhere('platformName', $key);
      })->when($request->status, function ($q) use ($request) {
         $status = $request->status;
         $q->where('orderStatusList', $status)
            ->orWhere('subOrderStatusList', $status);
      })
         ->orderBy('id', 'desc')
         ->paginate(20);
   }
}
