<?php

namespace App\Jobs;

use App\Models\Stock;
use App\Services\WebhookService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class WebhookStockJob implements ShouldQueue
{
    use Queueable;

    public $stock;

    /**
     * Create a new job instance.
     */
    public function __construct(Stock $stock)
    {
        $this->stock = $stock;
    }

    /**
     * Execute the job.
     */
    public function handle(WebhookService $service): void
    {
        $service->confirmedStock($this->stock);
    }
}
