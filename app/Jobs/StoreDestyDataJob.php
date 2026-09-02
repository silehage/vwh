<?php

namespace App\Jobs;

use App\Helpers\Helper;
use App\Models\DestyData;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Support\Facades\Log;
use Modules\Desty\Services\DestyDataService;

class StoreDestyDataJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 10; // Naikkan jatah percobaan jika tidak menggunakan dontRelease()
    public int $timeout = 90; // Batas waktu maksimal job berjalan sebelum dianggap timeout

    public $payload;

    /**
     * Create a new job instance.
     */
    public function __construct($payload)
    {
        $this->payload = $payload;
    }

    // /**
    //  * Terapkan middleware untuk mencegah tumpang tindih berdasarkan ID data
    //  */

    public function middleware()
    {
        // Kunci lock dibuat unik berdasarkan ID di dalam payload
        $uniqueKey = $this->payload['orderId'];

        return [
            (new WithoutOverlapping($uniqueKey))
                ->releaseAfter(5) // Jika lock aktif, kembalikan ke antrean dalam 5 detik
                ->expireAfter(60)  // Hapus lock otomatis setelah 60 detik jika job hang
        ];
    }

    /**
     * Execute the job.
     */
    public function handle(DestyDataService $service): void
    {
        $orderId = $this->payload['orderId'];
        $orderUpdateTime = Helper::dateUtcToLocale($this->payload['orderUpdateTime']);

        $existingData = DestyData::where('orderId', $orderId)->where('orderUpdateTime', '>', $orderUpdateTime)->exists();

        if ($existingData) {
            Log::debug('Existing desty data with new updated', [
                'orderId' => $orderId,
                'currentUpdated' => $orderUpdateTime,
                'lastUpdated' => DestyData::where('orderId', $orderId)->select('orderUpdateTime')->value('orderUpdateTime')
            ]);
            return;
        }
        $service->store($this->payload);
    }
}
