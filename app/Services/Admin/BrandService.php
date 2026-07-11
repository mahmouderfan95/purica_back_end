<?php
namespace App\Services\Admin;
use App\Enums\GeneralStatusEnum;
use App\Helper\FileUpload;
use App\Http\Resources\Admin\Brands\BrandCollection;
use App\Http\Resources\Admin\Brands\BrandResource;
use App\Repositories\Admin\BrandRepository;
use App\Traits\ApiResponseAble;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BrandService
{
    use ApiResponseAble,FileUpload;
    public function __construct(public BrandRepository $repository){}
    public function index($request) : JsonResponse
    {
        try{
            $categories = $this->repository->getModelWithPaginate($request);
            if(!$categories->isEmpty()){
                return $this->ApiSuccessResponse(BrandCollection::make($categories));
            }
            return $this->listResponse([]);
        }catch (\Exception $exception){
            Log::error('error of get all brands' . $exception->getMessage());
            return $this->ApiErrorResponse([],'something went wrong');
        }
    }
    public function store($request) : JsonResponse
    {
        try{
            $data = $request->validated();
            // Handle image upload if provided
            if ($request->hasFile('image')) {
                $data['image'] = $this->save_file($request->file('image'),'brands'); // Store in 'storage/app/public/packages'
            }
            // Create the package
            $brand = $this->repository->create([
                'name' => $data['name'],
                'image' => $data['image'],
                'status' => GeneralStatusEnum::ACTIVE,
            ]);

            return $this->ApiSuccessResponse(new BrandResource($brand),'success message');
        }catch (\Exception $exception){
            Log::error('error of store brand' . $exception->getMessage());
            return $this->ApiErrorResponse([], 'something went wrong');
        }
    }

    public function show($id) : JsonResponse
    {
        try{
            $brand = $this->repository->getModelById($id);
            if(!$brand)
                return $this->notFoundResponse();
            return $this->ApiSuccessResponse(new BrandResource($brand),'success message');
        }catch (\Exception $exception){
            Log::error('error of get brand details ' . $exception->getMessage());
            return $this->ApiErrorResponse([], 'something went wrong',500);
        }
    }

    public function update($request, $id) : JsonResponse
    {
        DB::beginTransaction();
        try{
            $data = $request->validated();

            // Find the package by ID
            $brand = $this->repository->getModelById($id);
            if (!$brand) {
                return $this->notFoundResponse();
            }

            // Handle image upload if provided
            if ($request->hasFile('image')) {
                // Delete the old image if it exists
                if ($brand->image) {
                    $this->remove_file('brands', $brand->image);
                }
                // Save the new image
                $data['image'] = $this->save_file($request->file('image'), 'brands');
            }
            $brand->update([
                'name' => $data['name'] ?? $brand['name'],
                'image' => $data['image'] ?? $brand['image'],
                'status' => $data['status'] ?? $brand['status'],
            ]);
            DB::commit();
            return $this->ApiSuccessResponse(new BrandResource($brand), 'Brand updated successfully.');
        }catch (\Exception $exception)
        {
            DB::rollBack();
            Log::error('error of update brand ' . $exception->getMessage());
            return $this->ApiErrorResponse([], 'something went wrong',500);
        }
    }

    public function destroy($id) : JsonResponse
    {
        try{
            $brand = $this->repository->getModelById($id);
            if ($brand) {
                // Check if an image exists and delete it
                if ($brand->image) {
                    $this->remove_file('brands', $brand->image);
                }
                // Delete the package
                $brand->delete();
                return $this->ApiSuccessResponse([],'brand deleted successfully');
            }
            return $this->notFoundResponse();
        }catch (\Exception $exception){
            return $this->ApiErrorResponse($exception->getMessage());
        }
    }
}
