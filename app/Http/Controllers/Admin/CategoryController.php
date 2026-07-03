<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Categories\StoreRequest;
use App\Http\Requests\Admin\Categories\UpdateRequest;
use App\Services\Admin\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(public CategoryService $categoryService){}
    public function index(Request $request) : JsonResponse
    {
        return $this->categoryService->index($request);
    }
    public function store(StoreRequest $request) : JsonResponse
    {
        return $this->categoryService->store($request);
    }
    public function show(int $id) : JsonResponse
    {
        return $this->categoryService->show($id);
    }
    public function update(UpdateRequest $request, int $id) : JsonResponse
    {
        return $this->categoryService->update($request, $id);
    }
    public function destroy(int $id) : JsonResponse
    {
        return $this->categoryService->destroy($id);
    }
    public function changeStatus(int $id,ChangeStatusRequest $request) : JsonResponse
    {
        return $this->categoryService->changeStatus($id,$request, );
    }
}
