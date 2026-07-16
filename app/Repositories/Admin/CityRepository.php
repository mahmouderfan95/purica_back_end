<?php
namespace App\Repositories\Admin;
use App\Models\City;
use Illuminate\Support\Collection;

class CityRepository
{
    public function getCityWithOutPaginate() :Collection
    {
        return $this->getModel()::query()
            ->with('country')
            ->where('country_id',1)
            ->select(['id', 'name', 'country_id'])
            ->get();
    }
    public function getCities($request){
        $search = $request->input('search',null);
        $lang = $request->header('lang','en');
        $allowedSorts = [
            'id',
            'created_at',
        ];

        $sortBy  = $request->get('sort_by', 'id');

        $sort_direction = $request->input('sort_dir','desc');

        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'id';
        }

        if (!in_array($sort_direction, ['asc', 'desc'])) {
            $sort_direction = 'desc';
        }
        $data = $this->getModel()::query()->with('country');
        $data->when($search,function($q,$search)use($lang){
            $q->where("name->$lang",'like',"%{$search}%");
        });
        $data->orderBy($sortBy, $sort_direction);
        return $data->paginate(PAGINATION_COUNT_ADMIN);
    }
    public function getModelById(int $id)
    {
        return $this->getModel()::query()->where('id', $id)->first();
    }
    public function store($data)
    {
        return $this->getModel()::create($data);
    }
    public function update($id, $data)
    {
        $category = $this->getModel()::find($id);
        if ($category) {
            return $category->update($data);
        }
        return false;
    }
    private function getModel() : City
    {
        return new  City();
    }
}
