<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Countries\StoreRequest;
use App\Http\Requests\Admin\Countries\UpdateRequest;
use App\Services\Admin\CountryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function __construct(public CountryService $countryService){}
    public function getCountries(Request $request): JsonResponse
    {
        return $this->countryService->getCountries($request);
    }
    public function index(Request $request) : JsonResponse
    {
        return $this->countryService->index($request);
    }
    public function store(StoreRequest $request) : JsonResponse
    {
        return $this->countryService->store($request);
    }
    public function update($id,UpdateRequest $request) : JsonResponse
    {
        return $this->countryService->update($id,$request);
    }
    public function destroy($id) : JsonResponse
    {
        return $this->countryService->destroy($id);
    }
}
