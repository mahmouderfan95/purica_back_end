<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Roles\StoreRequest;
use App\Http\Requests\Admin\Roles\UpdateRequest;
use App\Services\Admin\RolePermissionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RolePermissionController extends Controller
{
    public function __construct(public RolePermissionService $rolePermissionService){}
    public function index(Request $request) : JsonResponse
    {
        return $this->rolePermissionService->index($request);
    }
    public function store(StoreRequest $request) : JsonResponse
    {
        return $this->rolePermissionService->store($request);
    }
    public function show($id) : JsonResponse
    {
        return $this->rolePermissionService->show($id);
    }
    public function update(UpdateRequest $request,$id) : JsonResponse
    {
        return $this->rolePermissionService->update($request,$id);
    }
    public function destroy($id) : JsonResponse
    {
        return $this->rolePermissionService->destroy($id);
    }
    public function getPermissions() : JsonResponse
    {
        return $this->rolePermissionService->getPermissions();
    }
}
