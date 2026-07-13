<?php
namespace App\Services\Front;
use App\Http\Resources\Admin\Brands\BrandResource;
use App\Http\Resources\Admin\Categories\CategoryResource;
use App\Http\Resources\Admin\Products\ProductResource;
use App\Http\Resources\Admin\Sliders\SliderResource;
use App\Http\Resources\Front\Products\SingleProductResource;
use App\Repositories\Front\BrandRepository;
use App\Repositories\Front\CategoryRepository;
use App\Repositories\Front\ProductRepository;
use App\Repositories\Front\SettingRepository;
use App\Repositories\Front\SliderRepository;
use App\Traits\ApiResponseAble;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class HomepageService
{
    use ApiResponseAble;
    public function __construct(
        public SliderRepository $sliderRepository,
        public CategoryRepository $categoryRepository,
        public BrandRepository $brandRepository,
        public SettingRepository $settingRepository,
        public ProductRepository $productRepository,

    ){}
    public function index() : JsonResponse
    {
        try{
            $settings = $this->settingRepository->getSettings();
            $data = [];
            $data['sliders'] = SliderResource::collection($this->sliderRepository->getSliders());
            $data['mainCategories'] = CategoryResource::collection($this->categoryRepository->getMainCategories());
            $data['mainBrands'] = BrandResource::collection($this->brandRepository->getMainBrands());
            $data['products'] = SingleProductResource::collection($this->productRepository->getFeaturedProducts());
            $data['site_video'] =  $settings?->site_video;
            $data['bundles'] = [];
            return $this->ApiSuccessResponse($data);
        }catch (\Exception $exception)
        {
            Log::error('error for get homepage api ' . $exception->getMessage());
            return $this->ApiErrorResponse([],'something went wrong',500);
        }
    }
}
