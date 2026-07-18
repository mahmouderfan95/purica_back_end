<?php
namespace App\Services\Front;
use App\Http\Resources\Front\Ratings\RatingResource;
use App\Repositories\Front\RatingRepository;
use App\Traits\ApiResponseAble;
use Illuminate\Http\JsonResponse;

class RatingService
{
    use ApiResponseAble;
    public function __construct(public RatingRepository $repository){}
    public function store($request) : JsonResponse
    {
        try{
            $rating = $this->repository->createRating($request);
            if($rating)
                return $this->ApiSuccessResponse(RatingResource::make($rating),'rating created successfully');
            return $this->ApiErrorResponse([],'rating create failed');
        }catch (\Exception $exception){
            Log::error('rating error '. $exception->getMessage(),[
                'product' => $request->product_id
            ]);
            return $this->ApiErrorResponse([],trans('general.something_went_error'));
        }
    }
}
