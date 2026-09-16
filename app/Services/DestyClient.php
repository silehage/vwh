<?php

namespace App\Services;

use Exception;
use App\Models\DestyConfig;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DestyClient
{
   protected $base_url = 'https://api.desty.app/api';
   protected $accessToken;
   protected $config;

   public function __construct()
   {
      $this->config = DestyConfig::first();
      $this->accessToken = $this->config->access_token;

      $now = Carbon::now()->subMinute()->getTimestampMs();

      if ($now >= $this->config->token_expired_time || !$this->accessToken) {
         $this->requestAccessToken();
      }
   }

   public function requestAccessToken()
   {

      try {

         $url = $this->base_url . '/auth/token';
         $payload =  [
            'applyId' => $this->config->apply_id,
            'username' => $this->config->username,
            'mobile' => $this->config->mobile,
         ];

         $response = Http::asJson()->post($url, $payload);

         if ($response->failed()) {
            $response->throw();
         }

         $result = json_decode($response, true);

         if (isset($result['success']) && $result['success'] == true && isset($result['data'])) {

            $data = $result['data'];

            $lastToken = $this->config->access_token;

            $this->config->access_token = $data['accessToken'];
            $this->config->token_type = $data['tokenType'];
            $this->config->token_expired_time = $data['expireTime'];
            $this->config->last_token = $lastToken;
            $this->config->save();

            return $result['data'];
         } else {

            $msg = isset($result['msg']) ? $result['msg'] : 'Unknown Message';
            throw new Exception($msg);
         }
      } catch (\Throwable $th) {
         Log::error($th);
         throw $th;
      }
   }

   public function getWarehouses(array $payload)
   {
      try {
         $path = 'warehouse/list';
         $response =  $this->httpPost($path, $payload);
         if ($response->failed()) {
            $response->throw();
         }
         $result = json_decode($response, true);
         if ($result['success'] == true && isset($result['data'])) {
            return $result['data'];
         }
         $msg = isset($result['msg']) ? $result['msg'] : $response;
         throw new Exception($msg);
      } catch (\Throwable $th) {
         throw $th;
      }
   }
   public function inventorySyncStock(array $payload)
   {
      try {
         $path = 'inventory/stock/sync';
         $response =  $this->httpPost($path, $payload);
         if ($response->failed()) {
            $response->throw();
         }
         $result = json_decode($response, true);
         if ($result['success'] == true && isset($result['data'])) {
            return $result['data'];
         }
         $msg = isset($result['msg']) ? $result['msg'] : $response;
         throw new Exception($msg);
      } catch (\Throwable $th) {
         throw $th;
      }
   }

   public function getProductLists(array $payload)
   {

      try {
         $path = 'product/page';
         $response = $this->httpPost($path, $payload);
         if ($response->failed()) {
            $response->throw();
         }
         $result = json_decode($response, true);
         if ($result['success'] == true && isset($result['data'])) {
            return $result['data'];
         }
         $msg = isset($result['msg']) ? $result['msg'] : $response;
         throw new Exception($msg);
      } catch (\Throwable $th) {
         throw $th;
      }
   }
   public function getSkuDetail(array $payload)
   {

      try {
         $path = 'product/sku/detail';
         // $skuNumber = 'TVE016863';
         $response = $this->httpGet($path, $payload);
         if ($response->failed()) {
            $response->throw();
         }
         $result = json_decode($response, true);
         if ($result['success'] == true && isset($result['data'])) {
            return $result['data'];
         }
         $msg = isset($result['msg']) ? $result['msg'] : $response;
         throw new Exception($msg);
      } catch (\Throwable $th) {
         throw $th;
      }
   }
   public function printLabel(array $payload)
   {

      try {
         $path = '/order/print/label';

         $response = $this->httpGet($path, $payload);
         if ($response->failed()) {
            $response->throw();
         }
         $result = json_decode($response, true);
         if ($result['success'] == true && isset($result['data'])) {
            return $result['data'];
         } else {
            $msg = isset($result['msg']) ? $result['msg'] : $response;
         }
         throw new Exception($msg);
      } catch (\Throwable $th) {
         throw $th;
      }
   }
   public function printLabelPDF(array $payload)
   {

      try {
         $path = '/order/print/label/bytes';

         $response = $this->httpGet($path, $payload);
         if ($response->failed()) {
            $response->throw();
         }

         return $response;
      } catch (\Throwable $th) {
         throw $th;
      }
   }
   public function getReturnOrderLists(array $payload)
   {

      try {
         $path = 'return-order/page';
         $response = $this->httpPost($path, $payload);
         if ($response->failed()) {
            $response->throw();
         }
         $result = json_decode($response, true);
         if ($result['success'] == true && isset($result['data'])) {
            return $result['data'];
         }
         $msg = isset($result['msg']) ? $result['msg'] : $response;
         throw new Exception($msg);
      } catch (\Throwable $th) {
         throw $th;
      }
   }
   public function getReturnOrderDetail($payload)
   {

      try {
         $path = 'return-order/detail';
         $response = $this->httpGet($path, $payload);
         if ($response->failed()) {
            $response->throw();
         }
         $result = json_decode($response, true);
         if ($result['success'] == true && isset($result['data'])) {
            return $result['data'];
         }
         $msg = isset($result['msg']) ? $result['msg'] : $response;
         throw new Exception($msg);
      } catch (\Throwable $th) {
         throw $th;
      }
   }
   public function getOrderLists(array $payload)
   {

      try {
         $path = 'order/page';
         $response = $this->httpPost($path, $payload);
         if ($response->failed()) {
            $response->throw();
         }
         $result = json_decode($response, true);
         if ($result['success'] == true && isset($result['data'])) {
            return $result['data'];
         }
         $msg = isset($result['msg']) ? $result['msg'] : $response;
         throw new Exception($msg);
      } catch (\Throwable $th) {
         throw $th;
      }
   }
   public function getOrderDetail($payload)
   {

      try {
         $path = 'order/detail';
         $response = $this->httpGet($path, $payload);
         if ($response->failed()) {
            $response->throw();
         }
         $result = json_decode($response, true);
         if ($result['success'] == true && isset($result['data'])) {
            return $result['data'];
         }
         $msg = isset($result['msg']) ? $result['msg'] : $response;
         throw new Exception($msg);
      } catch (\Throwable $th) {
         throw $th;
      }
   }

   protected function httpPost(string $path, array $payload)
   {
      $url = $this->base_url . '/' . ltrim($path, '/');
      return Http::asJson()->withToken($this->accessToken)->post($url, $payload);
   }
   protected function httpGet(string $path, array $payload = [])
   {
      $url = $this->base_url . '/' . ltrim($path, '/');
      return Http::asJson()->withToken($this->accessToken)->get($url, $payload);
   }
}
