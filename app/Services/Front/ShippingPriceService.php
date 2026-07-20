<?php
namespace App\Services\Front;
use App\Http\Resources\Front\ShippingCostResource;
use App\Repositories\Front\ShippingCompanyRepository;
use App\Repositories\Front\ShippingPriceRepository;
use App\Traits\ApiResponseAble;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ShippingPriceService
{
    public function __construct(
        public ShippingPriceRepository $repository,
        public ShippingCompanyRepository $companyRepository
    ){}
    use ApiResponseAble;
    public function getShippingCost($cityId) : JsonResponse
    {
        try{
            $shippingCost  = $this->repository->getShippingCost($cityId);
            if ($shippingCost)
                return $this->ApiSuccessResponse(ShippingCostResource::class::make($shippingCost));
            return $this->listResponse([]);
        }catch (\Exception $exception){
            Log::error('error of get shipping cost' . $exception->getMessage());
            return $this->ApiErrorResponse([],'something went wrong',500);
        }
    }
}
