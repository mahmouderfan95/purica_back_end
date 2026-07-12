<?php
namespace App\Repositories\Admin;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductRepository
{
    public function getCountOfProducts()
    {
        return $this->getModel()::query()->count();
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
