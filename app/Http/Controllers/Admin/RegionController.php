<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Regions\StoreRequest;
use App\Http\Requests\Admin\Regions\UpdateRequest;
use App\Services\Admin\RegionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    public function __construct(public RegionService $regionService){}
    public function getRegions($cityId): JsonResponse
    {
        return $this->regionService->getRegions($cityId);
    }
    public function index(Request $request) : JsonResponse
    {
        return $this->regionService->index($request);
    }
    public function store(StoreRequest $request) : JsonResponse
    {
        return $this->regionService->store($request);
    }
    public function update($id,UpdateRequest $request) : JsonResponse
    {
        return $this->regionService->update($id,$request);
    }
    public function destroy($id) : JsonResponse
    {
        return $this->regionService->destroy($id);
    }
}
