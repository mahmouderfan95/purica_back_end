<?php
namespace App\Repositories\Admin;
use App\Models\Country;

class CountryRepository
{
    public function getCountries($request){
        $search = $request->input('search',null);
        $lang = $request->header('lang','en');
        $allowedSorts = [
            'id',
            'created_at',
        ];

        $sortBy  = $request->get('sort_by', 'id');
        $sortDir = $request->get('sort_dir', 'desc');

        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'id';
        }

        if (!in_array($sortDir, ['asc', 'desc'])) {
            $sortDir = 'desc';
        }
        $data = $this->getModel()::query();
        $data->when($search,function($q,$search)use($lang){
            $q->where("name->$lang",'like',"%{$search}%");
        });
        $data->orderBy($sortBy,$sortDir);
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
    private function getModel() : Country
    {
        return new  Country();
    }
}
