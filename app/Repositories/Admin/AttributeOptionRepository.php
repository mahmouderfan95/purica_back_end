<?php
namespace App\Repositories\Admin;
use App\Models\AttributeOption;
use Illuminate\Http\Request;

class AttributeOptionRepository
{
    public function getAttributeOptions(Request $request,$id){
        $search = $request->input('search');
        $lang = $request->header('lang', 'en');
        $sort_direction = $request->input('sort_direction', 'desc');

        $data = $this->getModel()::query()
            ->where('attribute_id', $id)
            ->when($search, function ($q) use ($search, $lang) {
                $q->where("name->{$lang}", 'like', "%{$search}%");
            })
            ->orderBy('id', $sort_direction);

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
    private function getModel() : AttributeOption
    {
        return new AttributeOption();
    }
}
