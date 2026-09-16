<?php

namespace App\Helpers;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;

class ApiResponse
{
   private static function send($data, $http_status = 200)
   {
      return response()->json($data, $http_status);
   }

   public static function success($value = null)
   {
      $response = [];
      $response['success'] = true;
      $response['message'] = 'Request Success';
      $response['results'] = $value;

      if (!is_null($value)) {
         if (is_array($value)) {
            $response['results'] = $value;
         } elseif (is_object($value)) {
            $response['results'] = $value;
         } else {
            $response['results'] = $value;
         }
      }

      return self::send($response);
   }

   public static function failed($error = null)
   {
      if (!app()->isProduction()) {
         Log::debug($error);
      }
      $response = [];
      $response['success'] = false;
      $response['message'] = null;

      $http_status = 400;

      if ($error instanceof ValidationException) {
         $response['message'] = 'The given data was invalid.';
         $response['errors'] = $error->errors();
         $http_status = 422;
      } elseif ($error instanceof QueryException) {
         $errors = [];
         if (!app()->isProduction()) {
            $errors['code'][] = $error->getCode();
            $errors['sql'][] = $error->getSql();
            $errors['bindings'][] = $error->getBindings();
         }
         $response['message'] = $error->getMessage();
         $response['errors'] = $errors;
      } elseif ($error instanceof Exception) {
         if (env('APP_DEBUG') == true) {
            $response['message'] = $error->getMessage();
            $response['errors'] = json_decode(json_encode($error->getTrace()));
         } else {
            $response['message'] = $error->getMessage();
         }
      } elseif ($error instanceof AuthenticationException) {
         $http_status = 403;
         if (env('APP_DEBUG') == true) {
            $response['message'] = $error->getMessage();
            $response['errors'] = json_decode(json_encode($error->getTrace()));
         } else {
            $response['message'] = $error->getMessage();
         }
      } else {
         if (is_object($error) || is_array($error)) {
            $response['message'] = json_encode($error);
         } else {
            $response['message'] = $error;
         }
      }

      return self::send($response, $http_status);
   }
}
