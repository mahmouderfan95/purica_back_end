<?php
namespace App\Repositories\Front;
use App\Models\Category;

class CategoryRepository
{
    public function getCategories()
    {
        return $this->getModel()::query()
            ->select('id','name','status')
            ->Active()
            ->orderByDesc('id')
            ->get();
    }
    public function getMainCategories()
    {
        return $this->getModel()::query()
            ->select('id','name','image')
            ->Active()
            ->take(10)
            ->orderByDesc('id')
            ->get();
    }
    private function getModel() : Category
    {
        return new Category();
    }
}
