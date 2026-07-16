<?php
namespace App\Services\Admin;
use App\Http\Resources\Admin\Regions\RegionCollection;
use App\Http\Resources\Admin\Regions\RegionResource;
use App\Repositories\Admin\RegionRepository;
use App\Traits\ApiResponseAble;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class RegionService
{
    use ApiResponseAble;
    public function __construct(public RegionRepository $regionRepository){}
    public function getRegions($cityId) : JsonResponse
    {
        $regions = $this->regionRepository->getAreaWithOutPaginate($cityId);
        if(!$regions->count() > 0)
            return $this->listResponse([]);
        return $this->ApiSuccessResponse(RegionResource::collection($regions));
    }
    public function index($request) : JsonResponse
    {
        try{
            $regions = $this->regionRepository->getRegions($request);
            if(!$regions)
                return $this->listResponse([]);
            return $this->ApiSuccessResponse(RegionCollection::make($regions),'regions list');
        }catch (\Exception $exception){
            Log::error('error of get regions' . $exception->getMessage());
            return $this->ApiErrorResponse([],'something went wrong',500);
        }
    }
    public function store($request) : JsonResponse
    {
        try{
            $country = $this->regionRepository->store([
                'name' => $request->name,
                'city_id' => $request->city_id,
            ]);
            return $this->ApiSuccessResponse(RegionResource::make($country),'region created');
        }catch (\Exception $exception){
            Log::error('error of store region' . $exception->getMessage());
            return $this->ApiErrorResponse([],'something went wrong',500);
        }
    }
    public function update($id,$request) : JsonResponse
    {
        try{
            $region = $this->regionRepository->getModelById($id);
            if(!$region)
                return $this->notFoundResponse();
            $region->update([
                'name' => $request->name,
                'city_id' => $request->city_id,
            ]);
            return $this->ApiSuccessResponse(RegionResource::make($region),'region created');
        }catch (\Exception $exception){
            Log::error('error of update region' . $exception->getMessage());
            return $this->ApiErrorResponse([],'something went wrong',500);
        }
    }
    public function destroy($id) : JsonResponse
    {
        try{
            $region = $this->regionRepository->getModelById($id);
            if(!$region)
                return $this->notFoundResponse();
            $region->delete();
            return $this->ApiSuccessResponse([],'region deleted');
        }catch (\Exception $exception){
            Log::error('error of delete region' . $exception->getMessage());
            return $this->ApiErrorResponse([],'something went wrong',500);
        }
    }
}
