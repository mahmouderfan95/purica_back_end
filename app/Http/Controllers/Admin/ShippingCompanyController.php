<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ShippingCompanies\StoreRequest;
use App\Http\Requests\Admin\ShippingCompanies\UpdateRequest;
use App\Services\Admin\ShippingCompanyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShippingCompanyController extends Controller
{
    public function __construct(public ShippingCompanyService $shippingCompanyService){}
    public function getShippingCompanies() : JsonResponse
    {
        return $this->shippingCompanyService->getShippingCompanies();
    }
    public function index(Request $request) : JsonResponse
    {
        return $this->shippingCompanyService->index($request);
    }
    public function store(StoreRequest $request) : JsonResponse
    {
        return $this->shippingCompanyService->store($request);
    }
    public function update($id,UpdateRequest $request) : JsonResponse
    {
        return $this->shippingCompanyService->update($id,$request);
    }
    public function destroy($id) : JsonResponse
    {
        return $this->shippingCompanyService->destroy($id);
    }
}
