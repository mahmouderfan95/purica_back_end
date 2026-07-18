<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Services\Front\CouponService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function __construct(public CouponService $couponService){}
    public function getValidCoupon($code) : JsonResponse
    {
        return $this->couponService->getValidCoupon($code);
    }
    public function getCouponValue($code,$cartTotal) : JsonResponse
    {
        return $this->couponService->getCouponValue($code,$cartTotal);
    }
    public function report($token) : JsonResponse
    {
        return $this->couponService->report($token);
    }
}
