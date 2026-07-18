<?php
namespace App\Services\Admin;
use App\Helper\FileUpload;
use App\Http\Resources\Admin\InfluencerEvaluations\InfluencerEvaluationCollection;
use App\Http\Resources\Admin\InfluencerEvaluations\InfluencerEvaluationResource;
use App\Http\Resources\Admin\Sliders\SliderResource;
use App\Repositories\Admin\InfluencerEvaluationRepository;
use App\Repositories\Front\SliderRepository;
use App\Traits\ApiResponseAble;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InfluencerEvaluationService
{
    use ApiResponseAble,FileUpload;
    public function __construct(
        public InfluencerEvaluationRepository $reviewRepository,
        public SliderRepository $sliderRepository,
    ){}
    public function index($request) : JsonResponse
    {
        try{
            $reviews = $this->reviewRepository->getReviews($request);
            if(!$reviews->isEmpty()){
                return $this->ApiSuccessResponse(InfluencerEvaluationCollection::make($reviews));
            }
            return $this->listResponse([]);
        }catch (\Exception $exception){
            Log::error('error of get all reviews' . $exception->getMessage());
            return $this->ApiErrorResponse([],'something went wrong');
        }
    }
    public function store($request) : JsonResponse
    {
        try{
            DB::beginTransaction();
            $data = $request->validated();
            // Handle image upload if provided
            if ($request->hasFile('image')) {
                $data['image'] = $this->save_file($request->file('image'),'reviews');
            }
            // Create the bundle
            $review = $this->reviewRepository->store([
                'name' => $data['name'],
                'image' => $data['image'],
                'url' => $data['url'],
                'status' => $data['status'],
            ]);
            DB::commit();
            return $this->ApiSuccessResponse(new InfluencerEvaluationResource($review),'success message');
        }catch (\Exception $exception){
            DB::rollBack();
            Log::error('error of store review' . $exception->getMessage());
            return $this->ApiErrorResponse([], 'something went wrong');
        }
    }
    public function show($id) : JsonResponse
    {
        try{
            $review = $this->reviewRepository->getModelById($id);
            if(!$review)
                return $this->notFoundResponse();
            return $this->ApiSuccessResponse(InfluencerEvaluationResource::make($review),'success message');
        }catch (\Exception $exception){
            Log::error('error of get review ' . $exception->getMessage());
            return $this->ApiErrorResponse([], 'something went wrong');
        }
    }
    public function update($request,$id) : JsonResponse
    {
        try{
            // Validate and prepare data
            $data = $request->validated();

            // Find the package by ID
            $review = $this->reviewRepository->getModelById($id);
            if (!$review) {
                return $this->notFoundResponse();
            }

            // Handle image upload if provided
            if ($request->hasFile('image')) {
                // Delete the old image if it exists
                if ($review->image) {
                    $this->remove_file('reviews', $review->image);
                }
                // Save the new image
                $data['image'] = $this->save_file($request->file('image'), 'reviews');
            }
            // Update the package
            $review->update([
                'name' => $data['name'] ?? $review->name,
                'image' => $data['image'] ?? $review->image,
                'url' => $data['url'] ?? $review->url,
                'status' => $data['status'] ?? $review->status,
            ]);
            return $this->ApiSuccessResponse(new InfluencerEvaluationResource($review), 'review updated successfully.');
        }catch (\Exception $exception){
            Log::error('error of update review' . $exception->getMessage());
            return $this->ApiErrorResponse([],'something went wrong',500);
        }
    }
    public function destroy($id) : JsonResponse
    {
        try{
            $review = $this->reviewRepository->getModelById($id);
            if ($review) {
                // Check if an image exists and delete it
                if ($review->image) {
                    $this->remove_file('reviews', $review->image);
                }
                // Delete the package
                $review->delete();
                return $this->ApiSuccessResponse([],'reviews deleted successfully');
            }
            return $this->notFoundResponse();
        }catch (\Exception $exception){
            Log::error('error of delete review' . $exception->getMessage());
            return $this->ApiErrorResponse($exception->getMessage(),500);
        }
    }
    public function getDataFromUser($request) : JsonResponse
    {
        try{
            $slider = $this->sliderRepository->getSliderReviewsPage('reviews');

            $data = [];
            $data['reviews'] = InfluencerEvaluationResource::collection(
                $this->reviewRepository->getReviews($request)
            );

            $data['slider_reviews_page'] = $slider
                ? SliderResource::make($slider)
                : [];
                return $this->ApiSuccessResponse($data);
        }catch (\Exception $exception)
        {
            Log::error('error for get reviews' . $exception->getMessage());
            return $this->ApiErrorResponse([],'something went wrong',500);
        }
    }
}
