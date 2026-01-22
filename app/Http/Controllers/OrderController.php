<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function __construct(
        protected OrderService $service
    ) {}
    public function index(Request $request)
    {
        return Inertia::render('Order/Index', [
            'data' => $this->service->index($request)
        ]);
    }
}
