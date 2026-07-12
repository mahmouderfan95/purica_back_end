<?php
namespace App\Repositories\Front;
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
    private function getModel() : Product
    {
        return new Product();
    }
}
