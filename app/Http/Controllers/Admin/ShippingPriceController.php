<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ShippingPrices\StoreRequest;
use App\Http\Requests\Admin\ShippingPrices\UpdateRequest;
use App\Services\Admin\ShippingPriceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShippingPriceController extends Controller
{
    public function __construct(public ShippingPriceService $shippingPriceService){}
    public function index(Request $request) : JsonResponse
    {
        return $this->shippingPriceService->index($request);
    }
    public function store(StoreRequest $request) : JsonResponse
    {
        return $this->shippingPriceService->store($request);
    }
    public function update($id,UpdateRequest $request) : JsonResponse
    {
        return $this->shippingPriceService->update($id,$request);
    }
    public function destroy($id) : JsonResponse
    {
        return $this->shippingPriceService->destroy($id);
    }
}
