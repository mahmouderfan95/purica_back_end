<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\Orders\StoreRequest;
use App\Services\Front\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(public OrderService $orderService){}
    public function index(Request $request): JsonResponse
    {
        return $this->orderService->index($request);
    }
    public function store(StoreRequest $request) : JsonResponse
    {
        return $this->orderService->store($request);
    }
    public function show($id): JsonResponse
    {
        return $this->orderService->show($id);
    }
    public function cancel(CancelOrderRequest $request) : JsonResponse
    {
        return $this->orderService->cancel($request);
    }
}
