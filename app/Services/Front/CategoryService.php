<?php
namespace App\Services\Front;
use App\Http\Resources\Admin\Categories\CategoryResource;
use App\Http\Resources\Admin\Products\ProductResource;
use App\Repositories\Front\CategoryRepository;
use App\Repositories\Front\ProductRepository;
use App\Traits\ApiResponseAble;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class CategoryService
{
    use ApiResponseAble;
    public function __construct(
        public CategoryRepository $repository,
        public ProductRepository $productRepository
    ){}
    public function index(): JsonResponse
    {
        try{
            $categories = $this->repository->getCategories();
            if(!$categories->count() > 0)
                return $this->listResponse();
            return $this->ApiSuccessResponse(CategoryResource::collection($categories));
        }catch (\Exception $exception){
            Log::error('error of get categories' . $exception->getMessage());
            return $this->ApiErrorResponse([],'something went wrong',500);
        }
    }
    public function show($id,$request): JsonResponse
    {
        try{
            $products = $this->productRepository->getProductsByCategory($id,$request);
            if(!$products->count() > 0)
                return $this->listResponse([]);
            return $this->ApiSuccessResponse(ProductResource::collection($products));
        }catch (\Exception $exception)
        {
            Log::error('error of get category details' . $exception->getMessage());
            return $this->ApiErrorResponse([],'something went wrong',500);
        }
    }
}
