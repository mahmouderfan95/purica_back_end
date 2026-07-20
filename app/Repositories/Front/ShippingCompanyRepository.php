<?php
namespace App\Repositories\Front;
use App\Models\ShippingCompany;
use Illuminate\Support\Collection;

class ShippingCompanyRepository
{
    public function getDefaultShippingCompany()
    {
        return $this->getModel()::query()
            ->where('is_default', '=', 1)
            ->first();
    }
    public function getShippingCompaniesWithoutPagination() : Collection
    {
        return $this->getModel()::query()
            ->select('id','name','email')
            ->get();
    }
    private function getModel() : ShippingCompany
    {
        return new ShippingCompany();
    }
}
