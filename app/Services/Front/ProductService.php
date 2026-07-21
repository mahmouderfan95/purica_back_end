<?php
namespace App\Services\Front;
use App\Http\Resources\Admin\Products\ProductVariantResource;
use App\Http\Resources\Admin\Sliders\SliderResource;
use App\Http\Resources\Front\Products\ProductCollection;
use App\Http\Resources\Front\Products\ProductResource;
use App\Models\Product;
use App\Repositories\Front\ProductRepository;
use App\Repositories\Front\ProductVariantRepository;
use App\Repositories\Front\SliderRepository;
use App\Traits\ApiResponseAble;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ProductService
{
    use ApiResponseAble;
    public function __construct(
        public ProductRepository $productRepository,
        public SliderRepository $sliderRepository,
        public ProductVariantRepository $productVariantRepository,
    ){}
    public function offers($request) : JsonResponse
    {
        try{
            $slider = $this->sliderRepository->getSliderReviewsPage('offers');
            $data = [];
            $data['offers'] = ProductCollection::make($this->productRepository->getProductsOffers($request));
            $data['slider_offers_page'] = $slider
                ? SliderResource::make($slider)
                : null;
            return $this->ApiSuccessResponse($data,'success message');
        }catch (\Exception $exception)
        {
            Log::error('error for get products offers '.$exception->getMessage());
            return $this->ApiErrorResponse([],'something went wrong',500);
        }
    }
    public function show($slug) : JsonResponse
    {
        try{
            $product = $this->productRepository->getModoeBySlug($slug);
            if(!$product)
                return $this->notFoundResponse();
            return $this->ApiSuccessResponse(ProductResource::make($product),'success message');
        }catch (\Exception $exception){
            Log::error('error of get show products'  .$exception->getMessage());
            return $this->ApiErrorResponse([],'something went wrong');
        }
    }
    public function search($request) : JsonResponse
    {
        try{
            $lang = $request->header('lang','en');
            $query = Product::query();
            $query->when('name', function ($q) use ($request,$lang) {
                $q->where("name->$lang",'like',"%{$request->search}%");
            });
            $query->when($request->min_price, fn($q) =>
            $q->where('price', '>=', $request->min_price)
            );

            $query->when($request->max_price, fn($q) =>
            $q->where('price', '<=', $request->max_price)
            );

            $query->when($request->category_ids, function ($q) use ($request) {
                $categoryIds = array_filter((array) $request->category_ids);
                if (!empty($categoryIds)) {
                    $q->whereIn('category_id', $categoryIds);
                }
            });

            $products = $query->with(['category','media','attributeOptions','variants','ratings'])
                ->paginate(PAGINATION_COUNT_WEB);
            return $this->ApiSuccessResponse(ProductCollection::make($products),'products retrieved successfully');
        }catch (\Exception $exception){
            Log::error('error for search product' . $exception->getMessage());
            return $this->ApiErrorResponse([],'something went wrong',500);
        }
    }
    public function getVariantPrice($request) : JsonResponse
    {
        try{
            $productVariant = $this->productVariantRepository->getVariantPrice($request);
            if(!$productVariant)
            {
                return $this->notFoundResponse();
            }
            return $this->ApiSuccessResponse(ProductVariantResource::make($productVariant),'success message');
        }catch (\Exception $exception)
        {
            Log::error('error for get product variant price' . $exception->getMessage());
            return $this->ApiErrorResponse([],'something went wrong');
        }
    }
}
