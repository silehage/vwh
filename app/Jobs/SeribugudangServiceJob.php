<?php

namespace App\Jobs;

use App\Services\SeribugudangService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SeribugudangServiceJob implements ShouldQueue
{
    use Queueable;

    public $data;

    /**
     * Create a new job instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(SeribugudangService $service): void
    {
        $service->store($this->data);
    }
}
