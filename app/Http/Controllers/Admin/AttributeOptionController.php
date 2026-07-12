<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AttributeOptions\StoreRequest;
use App\Http\Requests\Admin\AttributeOptions\UpdateRequest;
use App\Services\Admin\AttributeOptionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AttributeOptionController extends Controller
{
    public function __construct(public AttributeOptionService $attributeOptionService){}
    public function index(Request $request,$id) : JsonResponse
    {
        return $this->attributeOptionService->index($request,$id);
    }
    public function store(StoreRequest $request) : JsonResponse
    {
        return $this->attributeOptionService->store($request);
    }
    public function show($id) : JsonResponse
    {
        return $this->attributeOptionService->show($id);
    }
    public function update($id,UpdateRequest $request) : JsonResponse
    {
        return $this->attributeOptionService->update($id, $request);
    }
    public function destroy($id) : JsonResponse
    {
        return $this->attributeOptionService->destroy($id);
    }
}
