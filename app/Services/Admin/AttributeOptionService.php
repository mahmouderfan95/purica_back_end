<?php
namespace App\Services\Admin;
use App\Http\Resources\Admin\AttributeOptions\AttributeOptionCollection;
use App\Http\Resources\Admin\AttributeOptions\AttributeOptionResource;
use App\Repositories\Admin\AttributeOptionRepository;
use App\Traits\ApiResponseAble;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class AttributeOptionService
{
    use ApiResponseAble;
    public function __construct(public AttributeOptionRepository $attributeOptionRepository){}
    public function index($request,$id) : JsonResponse
    {
        try{
            $attributes = $this->attributeOptionRepository->getAttributeOptions($request,$id);
            if(!$attributes->isEmpty()){
                return $this->ApiSuccessResponse(AttributeOptionCollection::make($attributes));
            }
            return $this->listResponse([]);
        }catch (\Exception $exception){
            Log::error('error of get all attribute options' . $exception->getMessage());
            return $this->ApiErrorResponse([],'something went wrong');
        }
    }
    public function store($request) : JsonResponse
    {
        try{
            $data = $request->validated();
            // Create the attribute
            $attribute = $this->attributeOptionRepository->store([
                'name' => $data['name'],
                'status' => $data['status'],
                'value' => $data['value'] ?? null,
                'attribute_id' => $data['attribute_id'],
            ]);
            if($attribute)
                return $this->ApiSuccessResponse(new AttributeOptionResource($attribute),'success message');
        }catch (\Exception $exception){
            Log::error('error of store attribute options' . $exception->getMessage());
            return $this->ApiErrorResponse([], 'something went wrong');
        }
    }
    public function show($id) : JsonResponse
    {
        try{
            $attribute = $this->attributeOptionRepository->getModelById($id);
            if(!$attribute)
                return $this->notFoundResponse();
            return $this->ApiSuccessResponse(AttributeOptionResource::make($attribute),'success message');
        }catch (\Exception $exception){
            Log::error('error of get attribute options ' . $exception->getMessage());
            return $this->ApiErrorResponse([], 'something went wrong');
        }
    }
    public function update($id,$request) : JsonResponse
    {
        try{
            // Validate and prepare data
            $data = $request->validated();

            // Find the package by ID
            $attribute = $this->attributeOptionRepository->getModelById($id);
            if (!$attribute) {
                return $this->notFoundResponse();
            }
            // Update the package
            $attribute->update([
                'name' => $data['name'] ?? $attribute['name'],
                'status' => $data['status'] ?? $attribute['status'],
                'value' => $data['value'] ?? $attribute['value'],
                'attribute_id' => $data['attribute_id'] ?? $attribute['attribute_id'],
            ]);
            return $this->ApiSuccessResponse(new AttributeOptionResource($attribute), 'Attribute option updated successfully.');
        }catch (\Exception $exception){
            Log::error('error of update attribute option ' . $exception->getMessage());
            return $this->ApiErrorResponse([],'something went wrong');
        }
    }
    public function destroy($id) : JsonResponse
    {
        try{
            $attribute = $this->attributeOptionRepository->getModelById($id);
            if ($attribute) {
                // Delete the package
                $attribute->delete();
                return $this->ApiSuccessResponse([],'attribute option deleted successfully');
            }
            return $this->notFoundResponse();
        }catch (\Exception $exception){
            Log::error('error of delete attribute option ' . $exception->getMessage());
            return $this->ApiErrorResponse([],'something went wrong');
        }
    }
}
