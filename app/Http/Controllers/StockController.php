<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use Inertia\Inertia;
use App\Services\StockService;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function __construct(
        protected StockService $service,
        protected ProductService $productService
    ) {}
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = $this->service->index($request->stock_type ?? 'in');
        return Inertia::render('Stock/Index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return Inertia::render('Stock/Form', [
            'filters' => $this->productService->productFilters()
        ]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'stock_type' => ['required'],
            'description' => ['required'],
            'stock_items' => ['required', 'array']
        ]);

        $this->service->store($request);

        return to_route('stocks.index')->withMessage('Berhasil menambah stok');
    }
    public function confirmed(Request $request, $id)
    {
        $this->service->confirmed($request, $id);
        return to_route('stocks.index')->withMessage('Berhasil memperbarui stok');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $stock = $this->service->show($id);
        return Inertia::render('Stock/Show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return Inertia::render('Stock/Form', [
            'data' => $this->service->show($id),
            'filters' => $this->productService->productFilters()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'description' => ['required'],
            'stock_items' => ['required', 'array']
        ]);

        $this->service->update($request, $id);
        return to_route('stocks.index')->withMessage('Berhasil menambah stok');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->service->destroy($id);
        return to_route('stocks.index')->withMessage('Berhasil menghapus stok');
    }
}
