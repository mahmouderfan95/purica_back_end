<?php
namespace App\Services\Front;
use App\Http\Resources\Front\Products\ProductResource;
use App\Repositories\Front\FavoriteRepository;
use App\Traits\ApiResponseAble;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class FavoriteService
{
    use ApiResponseAble;
    public function __construct(public FavoriteRepository $repository){}
    public function toggle($request): JsonResponse
    {
        try {
            $productId = $request->input('product_id');
            $user = auth('api')->user();

            $added = $this->repository->toggleFavorite(
                $user,
                $request->attributes->get('guest_token'),
                $productId
            );

            $data = [
                'is_favorite' => $added,
            ];
            if (!$user) {
                $data['guest_token'] = $request->attributes->get('guest_token');
            }

            return $this->ApiSuccessResponse(
                $data,
                $added ? 'Added to favorites' : 'Removed from favorites'
            );

        } catch (\Exception $exception) {

            Log::error('toggle favorite error', [
                'user_id' => $user?->id,
                'guest_token' => $request->attributes->get('guest_token'),
                'product_id' => $productId,
                'error' => $exception->getMessage(),
            ]);

            return $this->ApiErrorResponse([], trans('general.something_went_wrong'));
        }
    }
    public function list($request): JsonResponse
    {
        $favorites = $this->repository->getFavorites(
            auth('api')->user(),
            $request->attributes->get('guest_token')
        );

        return $this->ApiSuccessResponse(
            ProductResource::collection($favorites)
        );
    }
}
