<?php
namespace App\Repositories\Admin;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandRepository
{
    public function getModelWithPaginate(Request $request){
        $search = $request->input('search',null);
        $lang = $request->header('lang','en');
        $data = $this->getModel()::query();
        $data->when($search,function($q,$search)use($lang){
            $q->where("name->$lang",'like',"%{$search}%");
        });
        $data->orderBy("id", 'desc');
        return $data->paginate(PAGINATION_COUNT_ADMIN);
    }
    public function getModelById(int $id)
    {
        return $this->getModel()::query()->where('id', $id)->first();
    }
    public function create($data)
    {
        return $this->getModel()::create($data);
    }
    private function getModel() : Brand
    {
        return new Brand();
    }
}
