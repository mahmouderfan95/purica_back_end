<?php
namespace App\Services\Admin;
use App\Helper\FileUpload;
use App\Http\Resources\Admin\Sliders\SliderCollection;
use App\Http\Resources\Admin\Sliders\SliderResource;
use App\Repositories\Admin\SliderRepository;
use App\Traits\ApiResponseAble;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class SliderService
{
    use ApiResponseAble,FileUpload;
    public function __construct(public SliderRepository $sliderRepository){}
    public function index($request) : JsonResponse
    {
        try{
            $sliders = $this->sliderRepository->getDataWithPaginate($request);
            if(!$sliders->isEmpty()){
                return $this->ApiSuccessResponse(SliderCollection::make($sliders));
            }
            return $this->listResponse([]);
        }catch (\Exception $exception){
            Log::error('error of get all sliders' . $exception->getMessage());
            return $this->ApiErrorResponse([],'something went wrong');
        }
    }
    public function store($request) : JsonResponse
    {
        try{
            $data = $request->validated();
            // Handle image upload if provided
            if ($request->hasFile('image')) {
                $data['image'] = $this->save_file($request->file('image'),'sliders'); // Store in 'storage/app/public/packages'
            }
            // Create the package
            $slider = $this->sliderRepository->store([
                'title' => $data['title'] ?? null,
                'description' => $data['description'] ?? null,
                'url' => $data['url'] ?? null,
                'image' => $data['image'],
                'status' => $data['status'],
                'type' => $data['type'] ?? null,
                'page_slug' => $data['page_slug'] ?? null,
                'position' => $data['position'] ?? null,
            ]);
            if($slider)
                return $this->ApiSuccessResponse(new SliderResource($slider),'success message');
        }catch (\Exception $exception){
            Log::error('error of store slider' . $exception->getMessage());
            return $this->ApiErrorResponse([], 'something went wrong');
        }
    }
    public function show($id) : JsonResponse
    {
        try{
            $slider = $this->sliderRepository->getModelById($id);
            if(!$slider)
                return $this->notFoundResponse();
            return $this->ApiSuccessResponse(SliderResource::make($slider),'success message');
        }catch (\Exception $exception){
            Log::error('error of get slider ' . $exception->getMessage());
            return $this->ApiErrorResponse([], 'something went wrong');
        }
    }
    public function update($request,$id) : JsonResponse
    {
        try{
            // Validate and prepare data
            $data = $request->validated();

            // Find the package by ID
            $slider = $this->sliderRepository->getModelById($id);
            if (!$slider) {
                return $this->notFoundResponse();
            }

            // Handle image upload if provided
            if ($request->hasFile('image')) {
                // Delete the old image if it exists
                if ($slider->image) {
                    $this->remove_file('sliders', $slider->image);
                }
                // Save the new image
                $data['image'] = $this->save_file($request->file('image'), 'sliders');
            }

            // Update the package
            $slider->update([
                'title' => $data['title'] ?? $slider['title'],
                'description' => $data['description'] ?? $slider['description'],
                'image' => $data['image'] ?? $slider['image'],
                'url' => $data['url'] ?? $slider['url'],
                'status' => $data['status'] ?? $slider['status'],
                'type' => $data['type'] ?? $slider['type'],
                'page_slug' => $data['page_slug'] ?? $slider['page_slug'],
                'position' => $data['position'] ?? $slider['position'],
            ]);
            return $this->ApiSuccessResponse(new SliderResource($slider), 'Slider updated successfully.');
        }catch (\Exception $exception){
            Log::error('error of update slider' . $exception->getMessage());
            return $this->ApiErrorResponse([],'something went wrong');
        }
    }
    public function destroy($id) : JsonResponse
    {
        try{
            $slider = $this->sliderRepository->getModelById($id);
            if ($slider) {
                // Check if an image exists and delete it
                if ($slider->image) {
                    $this->remove_file('sliders', $slider->image);
                }
                // Delete the package
                $slider->delete();
                return $this->ApiSuccessResponse([],'slider deleted successfully');
            }
            return $this->notFoundResponse();
        }catch (\Exception $exception){
            return $this->ApiErrorResponse($exception->getMessage());
        }
    }
}
