<?php
namespace App\Repositories\Admin;
use App\Models\ShippingCompany;
use Illuminate\Support\Collection;

class ShippingCompanyRepository
{
    public function getDefaultShippingCompany() :? ShippingCompany
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
    public function getShippingCompanies($request)
    {
        $name = $request->input("name");
        return $this->getModel()::query()
            ->select(['id', 'name','email','phone','website','is_default'])
            ->when($name, function ($query, $name) {
                return $query->where('name', 'like', '%' . $name . '%');
            })
            ->paginate(PAGINATION_COUNT_ADMIN);
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
    private function getModel() : ShippingCompany
    {
        return new ShippingCompany();
    }
}
