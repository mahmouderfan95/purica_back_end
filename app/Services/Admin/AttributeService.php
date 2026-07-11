<?php
namespace App\Services\Admin;
use App\Http\Resources\Admin\Attributes\AttributeCollection;
use App\Http\Resources\Admin\Attributes\AttributeResource;
use App\Repositories\Admin\AttributeRepository;
use App\Traits\ApiResponseAble;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class AttributeService
{
    use ApiResponseAble;
    public function __construct(public AttributeRepository $repository){}
    public function index($request) : JsonResponse
    {
        try{
            $attributes = $this->repository->getAttributes($request);
            if(!$attributes->isEmpty()){
                return $this->ApiSuccessResponse(AttributeCollection::make($attributes));
            }
            return $this->listResponse([]);
        }catch (\Exception $exception){
            Log::error('error of get all attributes' . $exception->getMessage());
            return $this->ApiErrorResponse([],'something went wrong');
        }
    }
    public function store($request) : JsonResponse
    {
        try{
            $data = $request->validated();
            // Create the attribute
            $attribute = $this->repository->store([
                'name' => $data['name'],
                'status' => $data['status'],
            ]);
            if($attribute)
                return $this->ApiSuccessResponse(new AttributeResource($attribute),'success message');
        }catch (\Exception $exception){
            Log::error('error of store attribute' . $exception->getMessage());
            return $this->ApiErrorResponse([], 'something went wrong');
        }
    }
    public function show($id) : JsonResponse
    {
        try{
            $attribute = $this->repository->getModelById($id);
            if(!$attribute)
                return $this->notFoundResponse();
            return $this->ApiSuccessResponse(AttributeResource::make($attribute),'success message');
        }catch (\Exception $exception){
            Log::error('error of get attribute ' . $exception->getMessage());
            return $this->ApiErrorResponse([], 'something went wrong');
        }
    }
    public function update($id,$request) : JsonResponse
    {
        try{
            // Validate and prepare data
            $data = $request->validated();

            // Find the package by ID
            $attribute = $this->repository->getModelById($id);
            if (!$attribute) {
                return $this->notFoundResponse();
            }
            // Update the package
            $attribute->update([
                'name' => $data['name'] ?? $attribute['name'],
                'status' => $data['status'] ?? $attribute['status'],
            ]);
            return $this->ApiSuccessResponse(new AttributeResource($attribute), 'Attribute updated successfully.');
        }catch (\Exception $exception){
            Log::error('error of update attribute ' . $exception->getMessage());
            return $this->ApiErrorResponse([],'something went wrong');
        }
    }
    public function destroy($id) : JsonResponse
    {
        try{
            $attribute = $this->repository->getModelById($id);
            if ($attribute) {
                // Delete the package
                $attribute->delete();
                return $this->ApiSuccessResponse([],'attribute deleted successfully');
            }
            return $this->notFoundResponse();
        }catch (\Exception $exception){
            Log::error('error of delete attribute ' . $exception->getMessage());
            return $this->ApiErrorResponse([],'something went wrong');
        }
    }
}
