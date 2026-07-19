<?php
namespace App\Repositories\Admin;
use App\Models\HomeBanner;
use Illuminate\Http\Request;

class HomeBannerRepository
{
    public function getDataWithPaginate(Request $request){
        $status = $request->input('status');
        $data = $this->getModel()::query();
        $data->when($status,function($q,$status){
            $q->where("status",$status,);
        });
        $data->orderByDesc('created_at');
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
    private function getModel() : HomeBanner
    {
        return new HomeBanner();
    }
}
