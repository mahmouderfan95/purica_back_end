<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Coupons\StoreRequest;
use App\Http\Requests\Admin\Coupons\UpdateRequest;
use App\Services\Admin\CouponService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function __construct(public CouponService $couponService){}
    public function index(Request $request) : JsonResponse
    {
        return $this->couponService->index($request);
    }
    public function store(StoreRequest $request) : JsonResponse
    {
        return $this->couponService->store($request);
    }
    public function update($id,UpdateRequest $request) : JsonResponse
    {
        return $this->couponService->update($id,$request);
    }
    public function destroy($id) : JsonResponse
    {
        return $this->couponService->destroy($id);
    }
}
