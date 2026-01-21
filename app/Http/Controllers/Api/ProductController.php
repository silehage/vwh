<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\ProductService;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Jobs\ProductStoreJob;

class ProductController extends Controller
{
    public function __construct(
        protected ProductService $productService
    ) {}

    public function store(ProductRequest $request)
    {
        try {
            ProductStoreJob::dispatch($request->validated());
            return response()->json([
                'success' => true,
                'message' => 'Succes',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function list(Request $request)
    {
        $data = $this->productService->list($request);
        return response()->json([
            'success' => true,
            'message' => 'Succes',
            'data' => $data
        ]);
    }
}
