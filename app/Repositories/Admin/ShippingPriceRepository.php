<?php
namespace App\Repositories\Admin;
use App\Models\ShippingPrice;

class ShippingPriceRepository
{
    public function getShippingPrices($request)
    {
        $name = $request->input("name");
        return $this->getModel()::query()
            ->with(['shippingCompany','city'])
            ->select(['id', 'shipping_company_id','city_id','price'])
            ->paginate(PAGINATION_COUNT_ADMIN);
    }
    public function checkShippingPriceExists($request)
    {
        $exists = $this->getModel()::query()
            ->where('shipping_company_id', $request->shipping_company_id)
            ->where('city_id', $request->city_id)
//            ->where('region_id', $request->region_id)
            ->exists();

        if ($exists) {
            return false;
        }
    }
    public function getModelById($id)
    {
        return $this->getModel()::query()
            ->where('id', $id)
            ->first();
    }

    public function create(array $data)
    {
        return $this->getModel()::query()->create($data);
    }

    public function update(array $data)
    {
        return $this->getModel()::query()->update($data);
    }
    private function getModel() : ShippingPrice
    {
        return new ShippingPrice();
    }
}
