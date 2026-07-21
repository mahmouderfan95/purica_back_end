<?php
namespace App\Repositories\Admin;
use App\Enums\OrderStatusEnum;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ProductRepository
{
    public function getCountOfProducts()
    {
        return $this->getModel()::query()->count();
    }
    public function getMostOrderd(Request $request) : Collection
    {
        return $this->getModel()::query()
            ->select(
                'products.id',
                'products.name',
                'products.image'
            )
            ->selectRaw('SUM(order_items.quantity) as total_quantity_ordered')
            ->selectRaw('COUNT(order_items.id) as total_orders')
            ->join('order_items', 'order_items.product_id', '=', 'products.id')
            ->groupBy('products.id', 'products.name', 'products.image')
            ->orderByDesc('total_quantity_ordered')
            ->limit(10)
            ->get();
    }
    public function getLeastOrdered(Request $request) : Collection
    {
        return $this->getModel()::query()
            ->select(
                'products.id',
                'products.name',
                'products.image'
            )
            ->selectRaw('COALESCE(SUM(order_items.quantity), 0) as total_quantity_ordered')
            ->selectRaw('COALESCE(COUNT(order_items.id), 0) as total_orders')
            ->leftJoin('order_items', 'order_items.product_id', '=', 'products.id')
            ->groupBy('products.id', 'products.name', 'products.image')
            ->orderBy('total_quantity_ordered')
            ->limit(10)
            ->get();
    }
    public function getTopOrdered($from = null, $to = null,$sortBy = null, $sortDir = 'desc')
    {
        $query = Product::query()
            ->select(
                'products.id',
                'products.name',
                'products.image'
            )
            ->selectRaw('SUM(order_items.quantity) as total_ordered')
            ->selectRaw("
            (
                SELECT DATE(order_items.created_at)
                FROM order_items
                WHERE order_items.product_id = products.id
                " . ($from && $to ? " AND order_items.created_at BETWEEN '$from' AND '$to' " : "") . "
                GROUP BY DATE(order_items.created_at)
                ORDER BY SUM(order_items.quantity) DESC
                LIMIT 1
            ) as peak_date
        ")
            ->join('order_items', 'order_items.product_id', '=', 'products.id')
            ->when($from && $to, function ($q) use ($from, $to) {
                $q->whereBetween('order_items.created_at', [$from, $to]);
            })
            ->groupBy('products.id', 'products.name', 'products.image');
        if (in_array($sortBy, ['id', 'name'])) {
            $query->orderBy("products.$sortBy", $sortDir);
        } else {
            // Default sorting
            $query->orderByDesc('total_ordered');
        }
        return $query->paginate(PAGINATION_COUNT_ADMIN);
    }
    public function getMostReturned($from = null, $to = null,$sortBy = null, $sortDir = 'desc')
    {
        $query = Product::query()
            ->select(
                'products.id',
                'products.name',
                'products.image'
            )
            ->selectRaw('COUNT(order_items.id) as total_ordered')
            ->selectRaw("
            (
                SELECT DATE(order_items.created_at)
                FROM order_items
                WHERE order_items.product_id = products.id
                " . ($from && $to ? " AND order_items.created_at BETWEEN '$from' AND '$to' " : "") . "
                GROUP BY DATE(order_items.created_at)
                ORDER BY SUM(order_items.quantity) DESC
                LIMIT 1
            ) as peak_date
        ")
            ->join('order_items', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.status', OrderStatusEnum::REFUNDED)
            ->when($from && $to, function ($q) use ($from, $to) {
                $q->whereBetween('orders.created_at', [$from, $to]);
            })
            ->groupBy('products.id', 'products.name', 'products.image');
        if (in_array($sortBy, ['id', 'name'])) {
            $query->orderBy("products.$sortBy", $sortDir);
        } else {
            // Default sorting
            $query->orderByDesc('total_ordered');
        }
        return $query->paginate(PAGINATION_COUNT_ADMIN);
    }
    public function getHighestRated()
    {
        return Product::query()
            ->select(
                'products.id',
                'products.name',
                'products.image'
            )
            ->selectRaw('COALESCE(AVG(ratings.rating),0) as avg_rating')
            ->leftJoin('ratings', 'ratings.product_id', '=', 'products.id')
            ->groupBy('products.id', 'products.name', 'products.image')
            ->orderByDesc('avg_rating')
            ->paginate(PAGINATION_COUNT_ADMIN);
    }
    public function getLowestRated()
    {
        return Product::query()
            ->select(
                'products.id',
                'products.name',
                'products.image'
            )
            ->selectRaw('COALESCE(AVG(ratings.rating),0) as avg_rating')
            ->leftJoin('ratings', 'ratings.product_id', '=', 'products.id')
            ->groupBy('products.id', 'products.name', 'products.image')
            ->orderBy('avg_rating')
            ->paginate(PAGINATION_COUNT_ADMIN);
    }
    public function getProducts(Request $request){
        $search = $request->input('search');
        $lang = $request->header('lang','en');
        $sort_direction = $request->input('sort_direction','desc');
        $isPaginate = $request->input('is_paginate',1);
        $data = $this->getModel()::query();
        $data->when($search,function($q,$search)use($lang){
            $q->where("name->$lang",'like',"%{$search}%");
        });
        $data->with(['category','brand', 'attributeOptions', 'media', 'variants']);
        $data->orderBy("id", $sort_direction);
        if ($isPaginate) {
            return $data->paginate(PAGINATION_COUNT_ADMIN);
        }
        return $data->get();
    }
    public function getModelById(int $id)
    {
        return $this->getModel()::query()
            ->with(['category','brand','attributeOptions', 'media', 'variants'])
            ->where('id', $id)->first();
    }
    public function store($data)
    {
        return $this->getModel()::query()->create($data);
    }
    public function update($id, $data)
    {
        $category = $this->getModel()::query()->find($id);
        if ($category) {
            return $category->update($data);
        }
        return false;
    }
    public function getModel() : Product
    {
        return new Product();
    }
}
