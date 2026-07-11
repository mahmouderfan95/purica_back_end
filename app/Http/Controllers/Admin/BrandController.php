<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Brands\StoreRequest;
use App\Http\Requests\Admin\Brands\UpdateRequest;
use App\Services\Admin\BrandService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function __construct(public BrandService $brandService){}

    public function index(Request $request) : JsonResponse
    {
        return $this->brandService->index($request);
    }
    public function store(StoreRequest $request) : JsonResponse
    {
        return $this->brandService->store($request);
    }
    public function show(int $id) : JsonResponse
    {
        return $this->brandService->show($id);
    }
    public function update(UpdateRequest $request, int $id) : JsonResponse
    {
        return $this->brandService->update($request, $id);
    }
    public function destroy(int $id) : JsonResponse
    {
        return $this->brandService->destroy($id);
    }
    public function changeStatus(int $id,ChangeStatusRequest $request) : JsonResponse
    {
        return $this->brandService->changeStatus($id,$request, );
    }
}
