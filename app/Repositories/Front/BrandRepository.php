<?php
namespace App\Repositories\Front;
use App\Models\Brand;

class BrandRepository
{
    public function getMainBrands()
    {
        return $this->getModel()::query()
            ->select('id','name','image')
            ->Active()
            ->take(10)
            ->orderByDesc('id')
            ->get();
    }
    private function getModel() : Brand
    {
        return new Brand();
    }
}
