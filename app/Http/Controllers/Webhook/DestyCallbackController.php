<?php

namespace App\Http\Controllers\Webhook;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Jobs\StoreDestyDataJob;

class DestyCallbackController extends Controller
{
   /**
    * Handle the incoming request.
    */
   public function __invoke(Request $request)
   {
      if ($request->method() != 'POST') {
         echo 'This route just for POST method only';
         die();
      }

      try {

         // $accessToken = $request->header('accessToken');
         // $token = str_replace("Bearer ", '', $accessToken);

         // Log::debug('IP : ' . $request->ip());

         $json = $request->getContent();
         $data = json_decode($json, true);

         // if (isset($data['storeName'])) {
         //    Log::debug('Log Webhook Bandung', [
         //       'IP' => $request->ip(),
         //       'orderId' => $data['orderId'],
         //       'orderSn' => $data['orderSn'],
         //       'storeName' => $data['storeName'],
         //       // 'storeId' => $data['storeId'],
         //    ]);
         // } else {
         //    Log::debug('Desty Payload Oanomali');
         //    Log::debug($data);
         // }

         StoreDestyDataJob::dispatch($data);

         return response()->json([
            'success' => true,
            'message' => 'Successfully'
         ]);
      } catch (\Throwable $th) {
         Log::error($th);
         return response()->json([
            'success' => true,
            'message' => 'Successfully'
         ]);
      }
   }
}
