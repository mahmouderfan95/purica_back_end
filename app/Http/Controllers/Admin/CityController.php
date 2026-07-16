<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Cities\StoreRequest;
use App\Http\Requests\Admin\Cities\UpdateRequest;
use App\Services\Admin\CityService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function __construct(public CityService $cityService){}
    public function getCities(Request $request) : JsonResponse
    {
        return $this->cityService->getCities($request);
    }
    public function index(Request $request) : JsonResponse
    {
        return $this->cityService->index($request);
    }
    public function store(StoreRequest $request) : JsonResponse
    {
        return $this->cityService->store($request);
    }
    public function update($id,UpdateRequest $request) : JsonResponse
    {
        return $this->cityService->update($id,$request);
    }
    public function destroy($id) : JsonResponse
    {
        return $this->cityService->destroy($id);
    }
}
