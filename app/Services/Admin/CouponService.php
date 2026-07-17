<?php
namespace App\Services\Admin;
use App\Http\Resources\Admin\Coupons\CouponCollection;
use App\Http\Resources\Admin\Coupons\CouponResource;
use App\Repositories\Admin\CouponRepository;
use App\Traits\ApiResponseAble;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CouponService
{
    use ApiResponseAble;
    public function __construct(public CouponRepository $couponRepository){}
    public function index($request) : JsonResponse
    {
        try{
            $coupons = $this->couponRepository->getCoupons($request);
            if(!$coupons)
                return $this->listResponse([]);
            return $this->ApiSuccessResponse(CouponCollection::make($coupons),'coupons list');
        }catch (\Exception $exception){
            Log::error('error of get coupons' . $exception->getMessage());
            return $this->ApiErrorResponse([],'something went wrong',500);
        }
    }
    public function store($request) : JsonResponse
    {
        try{
            $coupon = $this->couponRepository->create([
                'code' => $this->generateCouponCode('PUR'),
                'value' => $request->value,
                'min_order_total' => $request->min_order_total,
                'usage_limit' => $request->usage_limit,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'created_by' => auth('adminApi')->user()->id,
                'status' => $request->status,
                'token' => Str::uuid(),
            ]);
            return $this->ApiSuccessResponse(CouponResource::make($coupon),'coupon created');
        }catch (\Exception $exception){
            Log::error('error of create code' . $exception->getMessage());
            return $this->ApiErrorResponse([],'something went wrong',500);
        }
    }
    public function update($id,$request) : JsonResponse
    {
        try{
            $coupon = $this->couponRepository->getModelById($id);
            if(!$coupon)
                return $this->notFoundResponse();
            $coupon->update([
                'code' => $request->code,
                'value' => $request->value,
                'min_order_total' => $request->min_order_total,
                'usage_limit' => $request->usage_limit,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
            ]);
            return $this->ApiSuccessResponse(CouponResource::make($coupon),'coupon updates');
        }catch (\Exception $exception){
            Log::error('error of update coupon' . $exception->getMessage());
            return $this->ApiErrorResponse([],'something went wrong',500);
        }
    }
    public function destroy($id) : JsonResponse
    {
        try{
            $coupon = $this->couponRepository->getModelById($id);
            if(!$coupon)
                return $this->notFoundResponse();
            $coupon->delete();
            return $this->ApiSuccessResponse([],'coupon deleted');
        }catch (\Exception $exception){
            Log::error('error of delete coupon' . $exception->getMessage());
            return $this->ApiErrorResponse([],'something went wrong',500);
        }
    }
    private function generateCouponCode($prefix = 'PUR', $length = 6)
    {
        do {
            $code = $prefix . '-' . strtoupper(Str::random($length));
        } while ($this->couponRepository->exists(['code' => $code]));

        return $code;
    }
}
