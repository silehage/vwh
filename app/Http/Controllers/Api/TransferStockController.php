<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransferStockApiRequest;
use App\Services\StockService;
use Illuminate\Http\Request;

class TransferStockController extends Controller
{
    public function __construct(
        protected StockService $service
    ) {
    }
    public function store(TransferStockApiRequest $request)
    {
        try {
            $this->service->incomingTransferStock($request);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
