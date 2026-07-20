<?php
namespace App\Repositories\Front;
use App\Models\ShippingPrice;

class ShippingPriceRepository
{
    public function __construct(public ShippingCompanyRepository $shippingCompanyRepository){}
    public function getShippingCost($cityId)
    {
        $defaultShipping = $this->shippingCompanyRepository->getDefaultShippingCompany();
        return $this->getModel()::query()
            ->where('shipping_company_id',$defaultShipping->id)
            ->where('city_id',$cityId)
            ->first();
    }
    public function getShippingPrice($shippingCompanyId, $cityId): ?float
    {
        return $this->getModel()::query()
            ->where('shipping_company_id', $shippingCompanyId)
            ->where(function ($q) use ($cityId) {
                $q->where(function ($q) use ($cityId) {
                    $q->where('city_id', $cityId);
                })
                    ->orWhere(function ($q) use ($cityId) {
                        $q->where('city_id', $cityId)
                            ->whereNull('region_id');
                    })
                    ->orWhere(function ($q) {
                        $q->whereNull('city_id')
                            ->whereNull('region_id');
                    });
            })
            ->orderByRaw('region_id IS NULL, city_id IS NULL')
            ->value('price');
    }

    public function getModel() : ShippingPrice
    {
        return new ShippingPrice();
    }
}
