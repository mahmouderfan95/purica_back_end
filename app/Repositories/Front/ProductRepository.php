<?php
namespace App\Repositories\Front;
use App\Enums\GeneralStatusEnum;
use App\Models\Product;

class ProductRepository
{
    public function getProductsByCategory($categoryId,$request)
    {
        $lang = $request->header('lang', 'en');
        $search = $request->input('search');
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $brandId = $request->input('brand_id');
        $query = $this->getModel()::query()
            ->with(['category', 'brand' ,'attributeOptions', 'media', 'variants'])
            ->select(['id', 'name', 'description', 'slug', 'category_id', 'brand_id', 'price', 'price_after_discount','image'])
            ->Active()
            ->where('category_id', $categoryId);
        $query->when($search, function ($q, $search) use ($lang) {
            $q->where("name->{$lang}", 'like', "%{$search}%");
        });

        $query->when($request->category_ids, function ($q) use ($request) {
            $categoryIds = array_filter((array) $request->category_ids);
            if (!empty($categoryIds)) {
                $q->whereIn('category_id', $categoryIds);
            }
        });

        $query->when($minPrice, function ($q, $minPrice) {
            $q->where('price', '>=', $minPrice);
        });

        $query->when($maxPrice, function ($q, $maxPrice) {
            $q->where('price', '<=', $maxPrice);
        });

        $query->when($brandId, function ($q, $brandId) {
            $q->where('brand_id', $brandId);
        });

        $query->orderByDesc('updated_at');

        return $query->paginate(PAGINATION_COUNT_WEB);
    }
    public function getFeaturedProducts()
    {
        return $this->getModel()::query()
            ->select('id','name','image','price','price_after_discount','status','slug')
            ->Active()
            ->latest()
            ->take(4)
            ->get();
    }
    public function getProductsOffers($request)
    {
        $lang = $request->header('lang', 'en');
        $search = $request->input('search');
        $categoryId = $request->input('category_id');
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $query = $this->getModel()::query()
            ->with(['category', 'attributeOptions', 'media', 'variants','brand'])
            ->select(['id', 'name', 'description', 'slug', 'category_id','brand_id', 'price', 'price_after_discount','image'])
            ->whereNotNull('price_after_discount')
            ->active();
        $query->when($search, function ($q, $search) use ($lang) {
            $q->where("name->{$lang}", 'like', "%{$search}%");
        });

        $query->when($request->category_ids, function ($q) use ($request) {
            $categoryIds = array_filter((array) $request->category_ids);
            if (!empty($categoryIds)) {
                $q->whereIn('category_id', $categoryIds);
            }
        });

        $query->when($minPrice, function ($q, $minPrice) {
            $q->where('price', '>=', $minPrice);
        });

        $query->when($maxPrice, function ($q, $maxPrice) {
            $q->where('price', '<=', $maxPrice);
        });
        $query->orderByDesc('updated_at');

        return $query->paginate(PAGINATION_COUNT_ADMIN);
    }
    public function getModoeBySlug($slug)
    {
        $product = $this->getModel()::query()
            ->with(['category','brand', 'attributeOptions', 'media', 'variants','ratings'])
            ->where('slug', $slug)->first();
        if(!$product){
            return false;
        }
        $similarProducts = $this->getModel()::query()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with(['category', 'attributeOptions', 'media', 'variants'])
            ->paginate(PAGINATION_COUNT_ADMIN);
        $product->setRelation('similar_products', $similarProducts);
        return $product;
    }
    public function getSpecialOfferProduct()
    {
        return $this->getModel()::query()
            ->where('status', GeneralStatusEnum::ACTIVE)
            ->whereColumn('price_after_discount', '<', 'price')
            ->whereNotNull('discount_end_at')
            ->where('discount_end_at', '>', now())
            ->latest()
            ->first();
    }
    private function getModel() : Product
    {
        return new Product();
    }
}
