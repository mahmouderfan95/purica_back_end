<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Products\StoreRequest;
use App\Http\Requests\Admin\Products\UpdateRequest;
use App\Services\Admin\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(public ProductService $productService){}
    public function index(Request $request) : JsonResponse
    {
        return $this->productService->index($request);
    }
    public function store(StoreRequest $request) : JsonResponse
    {
        return $this->productService->store($request);
    }
    public function show(int $id) : JsonResponse
    {
        return $this->productService->show($id);
    }
    public function update(UpdateRequest $request, int $id) : JsonResponse
    {
        return $this->productService->update($request, $id);
    }
    public function destroy(int $id) : JsonResponse
    {
        return $this->productService->destroy($id);
    }
    public function changeStatus(int $id,Request $request) : JsonResponse
    {
        return $this->productService->changeStatus($id,$request, );
    }
}
