<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(public OrderService $orderService){}
    public function index(Request $request) : JsonResponse
    {
        return $this->orderService->index($request);
    }
    public function store(StoreRequest $request) : JsonResponse
    {
        return $this->orderService->store($request);
    }
    public function show($id) : JsonResponse
    {
        return $this->orderService->show($id);
    }
    public function update($id,UpdateRequest $request) : JsonResponse
    {
        return $this->orderService->update($id, $request);
    }
    public function assignShipping($id, AssignShippingRequest $request) : JsonResponse
    {
        return $this->orderService->assignShipping($id, $request);
    }
    public function updateStatus($id, UpdateStatusRequest $request) : JsonResponse
    {
        return $this->orderService->updateStatus($id, $request);
    }
    public function destroy($id) : JsonResponse
    {
        return $this->orderService->destroy($id);
    }
    public function bulkAssignShipping(BulkAssignShippingRequest $request) : JsonResponse
    {
        return $this->orderService->bulkAssignShipping($request);
    }
    public function deleteItem($id,$orderItemId) : JsonResponse
    {
        return $this->orderService->deleteItem($id,$orderItemId);
    }
}
