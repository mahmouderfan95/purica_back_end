<?php
namespace App\Repositories\Admin;
use App\Models\InfluencerEvaluation;
use Illuminate\Http\Request;

class InfluencerEvaluationRepository
{
    public function getReviews(Request $request){
        $search = $request->input('search',null);
        $status = $request->input('status');
        $sort_direction = $request->input('sort_direction','desc');
        $data = $this->getModel()::query();
        $data->when($search,function($q,$search){
            $q->where("name",'like',"%{$search}%");
        });
        $data->when($status,function($q,$status){
            $q->where("status",$status);
        });
        $data->orderBy("id", $sort_direction);
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
        $review = $this->getModel()::query()->find($id);
        if ($review) {
            return $review->update($data);
        }
        return false;
    }
    private function getModel() : InfluencerEvaluation
    {
        return new InfluencerEvaluation();
    }
}
