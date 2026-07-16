<?php
namespace App\Services\Admin;
use App\Http\Resources\Admin\Cities\CityCollection;
use App\Http\Resources\Admin\Cities\CityResource;
use App\Repositories\Admin\CityRepository;
use App\Traits\ApiResponseAble;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class CityService
{
    use ApiResponseAble;
    public function __construct(public CityRepository $cityRepository){}
    public function getCities() : JsonResponse
    {
        try{
            $cities = $this->cityRepository->getCityWithOutPaginate();
            if(!$cities)
                return $this->listResponse([]);
            return $this->ApiSuccessResponse(CityResource::collection($cities));
        }catch (\Exception $exception){
            Log::error('error of get cities' . $exception->getMessage());
            return $this->ApiErrorResponse([],'something went wrong',500);
        }
    }
    public function index($request) : JsonResponse
    {
        try{
            $countries = $this->cityRepository->getCities($request);
            if(!$countries)
                return $this->listResponse([]);
            return $this->ApiSuccessResponse(CityCollection::make($countries),'countries list');
        }catch (\Exception $exception){
            Log::error('error of get cities' . $exception->getMessage());
            return $this->ApiErrorResponse([],'something went wrong',500);
        }
    }
    public function store($request) : JsonResponse
    {
        try{
            $country = $this->cityRepository->store([
                'name' => $request->name,
                'country_id' => $request->country_id,
            ]);
            return $this->ApiSuccessResponse(CityResource::make($country),'city created');
        }catch (\Exception $exception){
            Log::error('error of store city' . $exception->getMessage());
            return $this->ApiErrorResponse([],'something went wrong',500);
        }
    }
    public function update($id,$request) : JsonResponse
    {
        try{
            $country = $this->cityRepository->getModelById($id);
            if(!$country)
                return $this->notFoundResponse();
            $country->update([
                'name' => $request->name,
                'country_id' => $request->country_id,
            ]);
            return $this->ApiSuccessResponse(CityResource::make($country),'city created');
        }catch (\Exception $exception){
            Log::error('error of update city' . $exception->getMessage());
            return $this->ApiErrorResponse([],'something went wrong',500);
        }
    }
    public function destroy($id) : JsonResponse
    {
        try{
            $country = $this->cityRepository->getModelById($id);
            if(!$country)
                return $this->notFoundResponse();
            $country->delete();
            return $this->ApiSuccessResponse([],'city deleted');
        }catch (\Exception $exception){
            Log::error('error of delete city' . $exception->getMessage());
            return $this->ApiErrorResponse([],'something went wrong',500);
        }
    }
}
