<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Services\Front\ShippingPriceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShippingPriceController extends Controller
{
    public function __construct(public ShippingPriceService $shippingPriceService){}
    public function getShippingCost($cityId) : JsonResponse
    {
        return $this->shippingPriceService->getShippingCost($cityId);
    }
}
