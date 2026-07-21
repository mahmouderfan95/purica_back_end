<?php
namespace App\Repositories\Admin;
use App\Enums\OrderStatusEnum;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryRepository
{
    public function categorySalesRatio()
    {
        return Category::query()
            ->select(
                'categories.id',
                'categories.name'
            )
            ->selectRaw('SUM(order_items.price) as total_sales')
            ->join('products', 'products.category_id', '=', 'categories.id')
            ->join('order_items', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.status', OrderStatusEnum::DELIVERED)
            ->groupBy('categories.id', 'categories.name')
            ->get();
    }
    public function getCountOfCategories()
    {
        return $this->getModel()::query()->count();
    }
    public function getCategoriesWithProductsCount()
    {
        return Category::query()
            ->withCount('products')
            ->orderByDesc('products_count')
            ->get();
    }
    public function getTotalProductsCount()
    {
        return Product::query()->Active()->count();
    }
    public function getCategories(Request $request){
        $search = $request->input('search',null);
        $lang = $request->header('lang','en');
        $sort_direction = $request->input('sort_direction','desc');
        $data = $this->getModel()::query();
        $data->when($search,function($q,$search)use($lang){
            $q->where("name->$lang",'like',"%{$search}%");
        });
        $data->orderBy("id", $sort_direction);
        return $data->paginate(25);
    }
    public function getModelById(int $id)
    {
        return $this->getModel()::query()->where('id', $id)->first();
    }
    public function create($data)
    {
        return $this->getModel()::create($data);
    }
    private function getModel() : Category
    {
        return new Category();
    }
}
