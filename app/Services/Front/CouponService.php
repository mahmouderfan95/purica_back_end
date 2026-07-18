<?php
namespace App\Services\Front;
use App\Http\Resources\Admin\Coupons\CouponResource;
use App\Repositories\Front\CouponRepository;
use App\Traits\ApiResponseAble;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class CouponService
{
    use ApiResponseAble;
    public function __construct(public CouponRepository $couponRepository){}
    public function getValidCoupon($code) : JsonResponse
    {
        try{
            $coupon = $this->couponRepository->findValidCoupon($code);
            if(!$coupon)
                return $this->notFoundResponse();
            return $this->ApiSuccessResponse(CouponResource::make($coupon));
        }catch (\Exception $exception)
        {
            Log::error('error of get valid coupon' . $exception->getMessage());
            return $this->ApiErrorResponse([],'something went wrong',500);
        }
    }
    public function getCouponValue($code,$cartTotal) : JsonResponse
    {
        try{
            $coupon = $this->couponRepository->findValidCoupon($code);

            if (!$coupon) {
                return $this->ApiErrorResponse([], 'الكوبون منتهى الصلاحية', 400);
            }

            // Check usage limit
            if ($coupon->usage_limit !== null && $coupon->used_count >= $coupon->usage_limit) {
                return $this->ApiErrorResponse([], 'تم تجاوز الحد الاقصى من الكوبون', 400);
            }

            // Check minimum order total
            if ($coupon->min_order_total && $cartTotal < $coupon->min_order_total) {
                return $this->ApiErrorResponse([], 'اجمالى الطلب اقل من الحد الادنى المطلوب لاستخدام للكوبون', 400);
            }

            return $this->ApiSuccessResponse($coupon->value,'success message');
        }catch (\Exception $exception)
        {
            Log::error('error of get valid coupon' . $exception->getMessage());
            return $this->ApiErrorResponse([],'something went wrong',500);
        }
    }
    public function report($token) : JsonResponse
    {
        try{
            $coupon = $this->couponRepository->getModelByToken($token);
            if(!$coupon)
                return $this->notFoundResponse();
            return $this->ApiSuccessResponse(CouponResource::make($coupon));
        }catch (\Exception $exception)
        {
            Log::error('error of get coupon report ' . $exception->getMessage());
            return $this->ApiErrorResponse([],'something went wrong',500);
        }
    }
}
