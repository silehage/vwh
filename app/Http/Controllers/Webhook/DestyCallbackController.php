<?php

namespace App\Http\Controllers\Webhook;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Jobs\DestyWebhookJob;
use Illuminate\Support\Facades\Cache;

class DestyCallbackController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
         if ($request->method() != 'POST') {
         echo 'This route just for POST method & for destyApp webhook callback only';
         die();
      }

      try {

        //  $accessToken = $request->header('accessToken');
        //  $token = str_replace("Bearer ", '', $accessToken);

         $json = $request->getContent();
         $data = json_decode($json, true);

         $cacheKey = 'CLB_DESTY_' . $data['orderId'];

         $timer = 30;
         $c = 0;

         if (Cache::has($cacheKey)) {
            $c = (int) Cache::get($cacheKey);
            DestyWebhookJob::dispatch($data)->delay($c);
            $delay = $c + $timer;
            Cache::put($cacheKey, $delay, now()->addSeconds($delay));
         } else {
            DestyWebhookJob::dispatch($data);
            Cache::put($cacheKey, $timer, now()->addSeconds($timer));
         }

         // Log::debug('CLB_DESTY ' . $data['orderId'] . ' DELAY ' . $c);

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
