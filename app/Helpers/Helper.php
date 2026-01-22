<?php

namespace App\Helpers;

use Illuminate\Support\Carbon;

class Helper
{

   public static function date_from_utc_to_locale($date)
   {
      if (is_numeric($date)) {
         $date = Carbon::createFromTimestampMs($date)->timezone('UTC')->toDateTimeString();
      }
      $dt = new \DateTime($date, new \DateTimeZone('UTC'));
      $dt->setTimezone(new \DateTimeZone(config('app.timezone')));

      return $dt->format('Y-m-d H:i:s');
   }
}
