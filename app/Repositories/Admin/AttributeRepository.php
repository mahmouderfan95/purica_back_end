<?php
namespace App\Repositories\Admin;
use App\Models\Attribute;
use Illuminate\Http\Request;

class AttributeRepository
{
    public function getAttributes(Request $request){
        $search = $request->input('search',null);
        $lang = $request->header('lang','en');
        $allowedSorts = [
            'id',
            'status',
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
        $data = $this->getModel()::query();

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
        $attribute = $this->getModel()::find($id);
        if ($attribute) {
            return $attribute->update($data);
        }
        return false;
    }
    private function getModel() : Attribute
    {
        return new Attribute();
    }
}
