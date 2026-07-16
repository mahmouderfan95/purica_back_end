<?php
namespace App\Services\Admin;
use App\Http\Resources\Admin\ShippingPrices\ShippingPriceCollection;
use App\Http\Resources\Admin\ShippingPrices\ShippingPriceResource;
use App\Repositories\Admin\ShippingPriceRepository;
use App\Traits\ApiResponseAble;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ShippingPriceService
{
    use ApiResponseAble;
    public function __construct(public ShippingPriceRepository $repository){}
    public function index($request) : JsonResponse
    {
        try{
            $prices = $this->repository->getShippingPrices($request);
            if(!$prices)
                return $this->listResponse([]);
            return $this->ApiSuccessResponse(ShippingPriceCollection::make($prices),'shipping price list');
        }catch (\Exception $exception){
            Log::error('error of get shipping prices' . $exception->getMessage());
            return $this->ApiErrorResponse([],'something went wrong',500);
        }
    }
    public function store($request) : JsonResponse
    {
        try{
        $checkShippingExists = $this->repository->checkShippingPriceExists($request);
        if($checkShippingExists === false){
            return $this->ApiErrorResponse([], 'Shipping price already exists',400);
        }
        $shipping = $this->repository->create([
            'shipping_company_id' => $request->shipping_company_id,
            'city_id' => $request->city_id,
//                'region_id' => $request->region_id,
            'price' => $request->price,
        ]);
        return $this->ApiSuccessResponse(ShippingPriceResource::make($shipping->load('shippingCompany','city')),'shipping price created');
        }catch (\Exception $exception){
            Log::error('error of store shipping price' . $exception->getMessage());
            return $this->ApiErrorResponse([],'something went wrong',500);
        }
    }
    public function update($id,$request) : JsonResponse
    {
        try{
            $shipping = $this->repository->getModelById($id);
            if(!$shipping)
                return $this->notFoundResponse();
            $shipping->update([
                'shipping_company_id' => $request->shipping_company_id,
                'city_id' => $request->city_id,
//                'region_id' => $request->city_id,
                'price' => $request->price,
            ]);
            return $this->ApiSuccessResponse(ShippingPriceResource::make($shipping->load('shippingCompany','city')),'shipping price updates');
        }catch (\Exception $exception){
            Log::error('error of update shipping price' . $exception->getMessage());
            return $this->ApiErrorResponse([],'something went wrong',500);
        }
    }
    public function destroy($id) : JsonResponse
    {
        try{
            $shipping = $this->repository->getModelById($id);
            if(!$shipping)
                return $this->notFoundResponse();
            $shipping->delete();
            return $this->ApiSuccessResponse([],'shipping price deleted');
        }catch (\Exception $exception){
            Log::error('error of delete shipping price' . $exception->getMessage());
            return $this->ApiErrorResponse([],'something went wrong',500);
        }
    }
}
