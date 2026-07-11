<?php
namespace App\Services\Admin;
use App\Http\Resources\Admin\Permission\PermissionResource;
use App\Http\Resources\Admin\Roles\RoleCollection;
use App\Http\Resources\Admin\Roles\RoleResource;
use App\Repositories\Admin\RolePermissionRepository;
use App\Traits\ApiResponseAble;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RolePermissionService
{
    use ApiResponseAble;
    public function __construct(public RolePermissionRepository $rolePermissionRepository){}
    public function index($request) : JsonResponse
    {
        try{
            $roles = $this->rolePermissionRepository->getRoles($request);
            if($roles->isNotEmpty())
                return $this->ApiSuccessResponse(RoleCollection::make($roles));
            return $this->listResponse([]);
        }catch (\Exception $exception){
            return $this->ApiErrorResponse([], $exception->getMessage());
        }
    }
    public function store($request) : JsonResponse
    {
        try{
            DB::beginTransaction();
            $createRole = $this->rolePermissionRepository->store($request);
            DB::commit();
            return $this->ApiSuccessResponse(RoleResource::make($createRole),'role created successfully');
        }catch (\Exception $exception){
            DB::rollBack();
            return $this->ApiErrorResponse([], $exception->getMessage());
        }
    }
    public function show($id) : JsonResponse
    {
        try{
            $role = $this->rolePermissionRepository->getModelById($id);
            if($role)
            {
                return $this->ApiSuccessResponse(RoleResource::make($role));
            }
            return $this->notFoundResponse();
        }catch (\Exception $exception){
            return $this->ApiErrorResponse([], $exception->getMessage());
        }
    }
    public function update($request, $id) : JsonResponse
    {
        $role = $this->rolePermissionRepository->update($request, $id);
        if (!$role)
            return $this->ApiErrorResponse(null, 'You cant update this id');
        return $this->ApiSuccessResponse(RoleResource::make($role), 'role updated...!');
    }
    public function destroy($id) : JsonResponse
    {
        try{
            $role = $this->rolePermissionRepository->getModelById($id);
            if(!$role){
                return $this->notFoundResponse();
            }
            $role->delete();
            return $this->ApiSuccessResponse([], 'role deleted successfully');
        }catch (\Exception $exception){
            return $this->ApiErrorResponse([], $exception->getMessage());
        }
    }
    public function getPermissions() : JsonResponse
    {
        try{
            $permissions = $this->rolePermissionRepository->getPermissions();
            if($permissions->isNotEmpty()){
                return $this->ApiSuccessResponse(PermissionResource::collection($permissions));
            }
            return $this->listResponse([]);
        }catch (\Exception $exception){
            Log::error('error of get permissions' . $exception->getMessage());
            return $this->ApiErrorResponse([], 'something went wrong');
        }
    }
}
