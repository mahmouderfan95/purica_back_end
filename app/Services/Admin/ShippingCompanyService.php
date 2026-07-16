<?php
namespace App\Services\Admin;
use App\Http\Resources\Admin\ShippingCompanies\ShippingCompanyCollection;
use App\Http\Resources\Admin\ShippingCompanies\ShippingCompanyResource;
use App\Repositories\Admin\ShippingCompanyRepository;
use App\Traits\ApiResponseAble;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ShippingCompanyService
{
    use ApiResponseAble;
    public function __construct(public ShippingCompanyRepository $shippingCompanyRepository){}
    public function getShippingCompanies() : JsonResponse
    {
        $shippings = $this->shippingCompanyRepository->getShippingCompaniesWithoutPagination();
        if(!$shippings->count() > 0)
            return $this->listResponse([]);
        return $this->ApiSuccessResponse(ShippingCompanyResource::collection($shippings),'shipping companies list');
    }
    public function index($request) : JsonResponse
    {
        try{
            $shippings = $this->shippingCompanyRepository->getShippingCompanies($request);
            if(!$shippings)
                return $this->listResponse([]);
            return $this->ApiSuccessResponse(ShippingCompanyCollection::make($shippings),'shipping companies list');
        }catch (\Exception $exception){
            Log::error('error of get shipping companies' . $exception->getMessage());
            return $this->ApiErrorResponse([],'something went wrong',500);
        }
    }
    public function store($request) : JsonResponse
    {
        try{
            $shipping = $this->shippingCompanyRepository->create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'website' => $request->website,
                'is_default' => $request->is_default,
            ]);
            return $this->ApiSuccessResponse(ShippingCompanyResource::make($shipping),'shipping created');
        }catch (\Exception $exception){
            Log::error('error of store shipping company' . $exception->getMessage());
            return $this->ApiErrorResponse([],'something went wrong',500);
        }
    }
    public function update($id,$request) : JsonResponse
    {
        try{
            $shipping = $this->shippingCompanyRepository->getModelById($id);
            if(!$shipping)
                return $this->notFoundResponse();
            $shipping->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'website' => $request->website,
                'is_default' => $request->is_default,
            ]);
            return $this->ApiSuccessResponse(ShippingCompanyResource::make($shipping),'shipping updates');
        }catch (\Exception $exception){
            Log::error('error of update shipping company' . $exception->getMessage());
            return $this->ApiErrorResponse([],'something went wrong',500);
        }
    }
    public function destroy($id) : JsonResponse
    {
        try{
            $shipping = $this->shippingCompanyRepository->getModelById($id);
            if(!$shipping)
                return $this->notFoundResponse();
            $shipping->delete();
            return $this->ApiSuccessResponse([],'shipping deleted');
        }catch (\Exception $exception){
            Log::error('error of delete shipping company' . $exception->getMessage());
            return $this->ApiErrorResponse([],'something went wrong',500);
        }
    }
}
