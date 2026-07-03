<?php
namespace App\Repositories\Admin;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryRepository
{
    public function getCategories(Request $request){
        $search = $request->input('search',null);
        $lang = $request->header('lang','en');
        $sort_direction = $request->input('sort_direction','desc');
        $data = $this->getModel()::query();
        $data->when($search,function($q,$search)use($lang){
            $q->where("name->$lang",'like',"%{$search}%");
        });
        $data->orderBy("id", $sort_direction);
        return $data->paginate(25);
    }
    public function getModelById(int $id)
    {
        return $this->getModel()::query()->where('id', $id)->first();
    }
    public function create($data)
    {
        return $this->getModel()::create($data);
    }
    private function getModel() : Category
    {
        return new Category();
    }
}
