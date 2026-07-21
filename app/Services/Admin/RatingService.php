<?php
namespace App\Services\Admin;
use App\Http\Resources\Admin\Ratings\ReviewsCollection;
use App\Http\Resources\Admin\Ratings\ReviewsResource;
use App\Repositories\Admin\RatingRepository;
use App\Traits\ApiResponseAble;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class RatingService
{
    use ApiResponseAble;
    public function __construct(public RatingRepository $repository){}
    public function index($request) : JsonResponse
    {
        try{
            $reviews = $this->repository->getReviews($request);
            if(!$reviews)
                return $this->listResponse([]);
            return $this->ApiSuccessResponse(ReviewsCollection::make($reviews),'reviews list');
        }catch (\Exception $exception){
            Log::error('error of get reviews' . $exception->getMessage());
            return $this->ApiErrorResponse([],'something went wrong',500);
        }
    }
    public function update($id,$request) : JsonResponse
    {
        try{
            $review = $this->repository->getModelById($id);
            if(!$review)
                return $this->notFoundResponse();
            $review->update([
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);
            return $this->ApiSuccessResponse(ReviewsResource::make($review),'Review updates');
        }catch (\Exception $exception){
            Log::error('error of update review' . $exception->getMessage());
            return $this->ApiErrorResponse([],'something went wrong',500);
        }
    }
    public function destroy($id) : JsonResponse
    {
        try{
            $review = $this->repository->getModelById($id);
            if(!$review)
                return $this->notFoundResponse();
            $review->delete();
            return $this->ApiSuccessResponse([],'review deleted');
        }catch (\Exception $exception){
            Log::error('error of delete coupon' . $exception->getMessage());
            return $this->ApiErrorResponse([],'something went wrong',500);
        }
    }
}
