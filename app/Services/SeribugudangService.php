<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SeribugudangService
{
    protected $base_url = 'http://localhost:8000';

   public function __construct()
   {
      if (app()->environment(['production', 'development'])) {
         $this->base_url = config('warehouse.seribugudang_base_url');
      }
   }

   public function store($payload)
   {
      try {
         $path = '/api/desty/orders/webhook';
         $url = $this->buildUrl($path);

         $payload['source'] = 'SC-BANDUNG';

         $response = Http::asJson()->post($url, $payload);

         if ($response->failed()) {
            $response->throw();
         }

         // Log::debug($response->json());
      } catch (\Throwable $th) {
         Log::error($th);
      }
   }
   protected function buildUrl($path)
   {
      return  rtrim($this->base_url, '/') . '/' . ltrim($path, '/');
   }
}