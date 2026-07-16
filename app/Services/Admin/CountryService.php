<?php
namespace App\Services\Admin;
use App\Http\Resources\Admin\Countries\CountryCollection;
use App\Http\Resources\Admin\Countries\CountryResource;
use App\Repositories\Admin\CountryRepository;
use App\Traits\ApiResponseAble;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class CountryService
{
    use ApiResponseAble;
    public function __construct(public CountryRepository $countryRepository){}
    public function getCountries($request) : JsonResponse
    {
        $countries = $this->countryRepository->getCountries($request);
        if(!$countries)
            return $this->listResponse([]);
        return $this->ApiSuccessResponse(CountryResource::collection($countries));
    }
    public function index($request) : JsonResponse
    {
        try{
            $countries = $this->countryRepository->getCountries($request);
            if(!$countries)
                return $this->listResponse([]);
            return $this->ApiSuccessResponse(CountryCollection::make($countries),'countries list');
        }catch (\Exception $exception){
            Log::error('error of get countries' . $exception->getMessage());
            return $this->ApiErrorResponse([],'something went wrong',500);
        }
    }
    public function store($request) : JsonResponse
    {
        try{
            $country = $this->countryRepository->store([
                'name' => $request->name,
                'code' => $request->code,
            ]);
            return $this->ApiSuccessResponse(CountryResource::make($country),'country created');
        }catch (\Exception $exception){
            Log::error('error of store country' . $exception->getMessage());
            return $this->ApiErrorResponse([],'something went wrong',500);
        }
    }
    public function update($id,$request) : JsonResponse
    {
        try{
            $country = $this->countryRepository->getModelById($id);
            if(!$country)
                return $this->notFoundResponse();
            $country->update([
                'name' => $request->name,
                'code' => $request->code,
            ]);
            return $this->ApiSuccessResponse(CountryResource::make($country),'country created');
        }catch (\Exception $exception){
            Log::error('error of update country' . $exception->getMessage());
            return $this->ApiErrorResponse([],'something went wrong',500);
        }
    }
    public function destroy($id) : JsonResponse
    {
        try{
            $country = $this->countryRepository->getModelById($id);
            if(!$country)
                return $this->notFoundResponse();
            $country->delete();
            return $this->ApiSuccessResponse([],'country deleted');
        }catch (\Exception $exception){
            Log::error('error of delete country' . $exception->getMessage());
            return $this->ApiErrorResponse([],'something went wrong',500);
        }
    }
}
