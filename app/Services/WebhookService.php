<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class WebhookService
{
   protected $base_url = 'http://valir.test';

   public function __construct()
   {
      if (app()->environment(['production', 'development'])) {
         $this->base_url = 'https://valir.id';
      }
   }

   public function confirmedStock($stock)
   {


      try {
         $path = '/api/webhook/transfer-stock';

         $url = $this->buildUrl($path);

         $payload = [
            'reference' => $stock->reference,
            'status' => $stock->status,
            'confirmed_by' => $stock->confirmed_by,
            'confirmed_at' => $stock->confirmed_at,
         ];

         $response = Http::post($url, $payload);

         if ($response->failed()) {
            $response->throw();
         }

         Log::debug($response->json());
      } catch (\Throwable $th) {
         Log::error($th);
      }
   }

   protected function buildUrl($path)
   {
      return  rtrim($this->base_url, '/') . '/' . ltrim($path, '/');
   }
}
