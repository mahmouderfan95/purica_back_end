<?php
namespace App\Services\Admin;
use App\Helper\FileUpload;
use App\Http\Resources\Admin\HomeBanners\HomeBannerCollection;
use App\Http\Resources\Admin\HomeBanners\HomeBannerResource;
use App\Repositories\Admin\HomeBannerRepository;
use App\Traits\ApiResponseAble;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HomeBannerService
{
    use ApiResponseAble,FileUpload;
    public function __construct(public HomeBannerRepository $homeBannerRepository){}
    public function index($request) : JsonResponse
    {
        try{
            $categories = $this->homeBannerRepository->getDataWithPaginate($request);
            if(!$categories->isEmpty()){
                return $this->ApiSuccessResponse(HomeBannerCollection::make($categories));
            }
            return $this->listResponse([]);
        }catch (\Exception $exception){
            Log::error('error of get all categories' . $exception->getMessage());
            return $this->ApiErrorResponse([],'something went wrong');
        }
    }
    public function store($request) : JsonResponse
    {
        DB::beginTransaction();
        try{
            $data = $request->validated();
            // Handle image upload if provided
            if ($request->hasFile('image')) {
                $data['image'] = $this->save_file($request->file('image'),'home_banners'); // Store in 'storage/app/public/packages'
            }
            // Create the package
            $category = $this->homeBannerRepository->create([
                'title' => $data['title'],
                'button_text' => $data['button_text'],
                'image' => $data['image'],
                'button_link' => $data['button_link'],
                'sort' => $data['sort'] ?? null,
            ]);
            DB::commit();
            return $this->ApiSuccessResponse(new HomeBannerResource($category),'success message');
        }catch (\Exception $exception){
            DB::rollBack();
            Log::error('error of store category' . $exception->getMessage());
            return $this->ApiErrorResponse([], 'something went wrong');
        }
    }

    public function show($id) : JsonResponse
    {
        try{
            $category = $this->homeBannerRepository->getModelById($id);
            if(!$category)
                return $this->notFoundResponse();
            return $this->ApiSuccessResponse(new HomeBannerResource($category));
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
            $category = $this->homeBannerRepository->getModelById($id);
            if (!$category) {
                return $this->notFoundResponse();
            }

            // Handle image upload if provided
            if ($request->hasFile('image')) {
                // Delete the old image if it exists
                if ($category->image) {
                    $this->remove_file('home_banners', $category->image);
                }
                // Save the new image
                $data['image'] = $this->save_file($request->file('image'), 'home_banners');
            }
            $category->update([
                'title' => $data['title'] ?? $category->title,
                'button_text' => $data['button_text'] ?? $category->button_text,
                'image' => $data['image'] ?? $category->image,
                'button_link' => $data['button_link'] ?? $category->button_link,
                'sort' => $data['sort'] ?? null,
            ]);
            DB::commit();
            return $this->ApiSuccessResponse(new HomeBannerResource($category), 'banner updated successfully.');
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
            $category = $this->homeBannerRepository->getModelById($id);
            if ($category) {
                // Check if an image exists and delete it
                if ($category->image) {
                    $this->remove_file('home_banners', $category->image);
                }
                // Delete the package
                $category->delete();
                return $this->ApiSuccessResponse([],'banner deleted successfully');
            }
            return $this->notFoundResponse();
        }catch (\Exception $exception){
            return $this->ApiErrorResponse($exception->getMessage());
        }
    }
}
