<?php

namespace App\Helpers;

use Illuminate\Support\Carbon;

class Helper
{

   public static function isMilliseconds($timestamp)
   {
      // A 10-digit timestamp is in seconds; 13 digits is in milliseconds
      return strlen((string)$timestamp) >= 13;
   }

   public static function dateUtcToLocale($date)
   {
      if (is_numeric($date)) {
         if (self::isMilliseconds($date)) {
            $date = Carbon::createFromTimestampMs($date)->timezone('UTC')->toDateTimeString();
         } else {
            $date = Carbon::createFromTimestamp($date)->timezone('UTC')->toDateTimeString();
         }
      }
      $dt = new \DateTime($date, new \DateTimeZone('UTC'));
      $dt->setTimezone(new \DateTimeZone(config('app.timezone')));

      return $dt->format('Y-m-d H:i:s');
   }
}
