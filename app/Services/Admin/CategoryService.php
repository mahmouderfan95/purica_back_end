<?php
namespace App\Services\Admin;
use App\Helper\FileUpload;
use App\Http\Resources\Admin\Categories\CategoryCollection;
use App\Http\Resources\Admin\Categories\CategoryResource;
use App\Repositories\Admin\CategoryRepository;
use App\Traits\ApiResponseAble;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CategoryService
{
    use ApiResponseAble,FileUpload;
    public function __construct(public CategoryRepository $categoryRepository){}
    public function index($request) : JsonResponse
    {
        try{
            $categories = $this->categoryRepository->getCategories($request);
            if(!$categories->isEmpty()){
                return $this->ApiSuccessResponse(CategoryCollection::make($categories));
            }
            return $this->listResponse([]);
        }catch (\Exception $exception){
            Log::error('error of get all categories' . $exception->getMessage());
            return $this->ApiErrorResponse([],'something went wrong');
        }
    }
    public function store($request) : JsonResponse
    {
        try{
            $data = $request->validated();
            // Handle image upload if provided
            if ($request->hasFile('image')) {
                $data['image'] = $this->save_file($request->file('image'),'categories'); // Store in 'storage/app/public/packages'
            }
            if ($request->hasFile('banner_image')) {
                $data['banner_image'] = $this->save_file($request->file('image'),'categories/banners'); // Store in 'storage/app/public/packages'
            }
            // Create the package
            $category = $this->categoryRepository->create([
                'name' => $data['name'],
                'image' => $data['image'],
                'banner_image' => $data['banner_image'],
                'status' => 'active',
            ]);

            return $this->ApiSuccessResponse(new CategoryResource($category),'success message');
        }catch (\Exception $exception){
            Log::error('error of store category' . $exception->getMessage());
            return $this->ApiErrorResponse([], 'something went wrong');
        }
    }

    public function show($id) : JsonResponse
    {
        try{
            $category = $this->categoryRepository->getModelById($id);
            if(!$category)
                return $this->notFoundResponse();
            return $this->ApiSuccessResponse(new CategoryResource($category));
        }catch (\Exception $exception){
            Log::error('error of get category details ' . $exception->getMessage());
            return $this->ApiErrorResponse([], 'something went wrong',500);
        }
    }

    public function update($request, $id) : JsonResponse
    {
        DB::beginTransaction();
        try{
            $data = $request->validated();

            // Find the package by ID
            $category = $this->categoryRepository->getModelById($id);
            if (!$category) {
                return $this->notFoundResponse();
            }

            // Handle image upload if provided
            if ($request->hasFile('image')) {
                // Delete the old image if it exists
                if ($category->image) {
                    $this->remove_file('categories', $category->image);
                }
                // Save the new image
                $data['image'] = $this->save_file($request->file('image'), 'categories');
            }
            if ($request->hasFile('banner_image')) {
                // Delete the old image if it exists
                if ($category->banner_image) {
                    $this->remove_file('categories/banners', $category->banner_image);
                }
                // Save the new image
                $data['banner_image'] = $this->save_file($request->file('banner_image'), 'categories/banners');
            }
            $category->update([
                'name' => $data['name'] ?? $category['name'],
                'image' => $data['image'] ?? $category['image'],
                'banner_image' => $data['banner_image'] ?? $category['banner_image'],
                'status' => $data['status'] ?? $category['status'],
            ]);
            DB::commit();
            return $this->ApiSuccessResponse(new CategoryResource($category), 'Category updated successfully.');
        }catch (\Exception $exception)
        {
            DB::rollBack();
            Log::error('error of update category ' . $exception->getMessage());
            return $this->ApiErrorResponse([], 'something went wrong',500);
        }
    }

    public function destroy($id) : JsonResponse
    {
        try{
            $category = $this->categoryRepository->getModelById($id);
            if ($category) {
                // Check if an image exists and delete it
                if ($category->image) {
                    $this->remove_file('categories', $category->image);
                }
                if ($category->banner_image) {
                    $this->remove_file('categories/banners', $category->banner_image);
                }
                // Delete the package
                $category->delete();
                return $this->ApiSuccessResponse([],'category deleted successfully');
            }
            return $this->notFoundResponse();
        }catch (\Exception $exception){
            return $this->ApiErrorResponse($exception->getMessage());
        }
    }
}
