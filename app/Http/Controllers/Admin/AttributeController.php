<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Attributes\StoreRequest;
use App\Http\Requests\Admin\Attributes\UpdateRequest;
use App\Services\Admin\AttributeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    public function __construct(public AttributeService $attributeService){}
    public function index(Request $request) : JsonResponse
    {
        return $this->attributeService->index($request);
    }
    public function store(StoreRequest $request) : JsonResponse
    {
        return $this->attributeService->store($request);
    }
    public function show($id) : JsonResponse
    {
        return $this->attributeService->show($id);
    }
    public function update($id,UpdateRequest $request) : JsonResponse
    {
        return $this->attributeService->update($id, $request);
    }
    public function destroy($id) : JsonResponse
    {
        return $this->attributeService->destroy($id);
    }
}
