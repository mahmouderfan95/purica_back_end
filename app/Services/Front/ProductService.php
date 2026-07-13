<?php
namespace App\Services\Front;
use App\Http\Resources\Admin\Sliders\SliderResource;
use App\Http\Resources\Front\Products\ProductCollection;
use App\Http\Resources\Front\Products\ProductResource;
use App\Repositories\Front\ProductRepository;
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
}
