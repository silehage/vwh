<?php

namespace App\Jobs;

use App\Models\DestyData;
use App\Services\SeribugudangService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SeribugudangPushDestyDataJob implements ShouldQueue
{
    use Queueable;

    public DestyData $destyData;

    /**
     * Create a new job instance.
     */
    public function __construct(DestyData $destyData)
    {
        $this->destyData = $destyData;
    }

    /**
     * Execute the job.
     */
    public function handle(SeribugudangService $service): void
    {
        $service->storeDestyData($this->destyData);
    }
}
