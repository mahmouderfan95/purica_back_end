<?php
namespace App\Repositories\Admin;
use App\Models\Slider;
use Illuminate\Http\Request;

class SliderRepository
{
    public function getDataWithPaginate(Request $request){
        $allowedSorts = [
            'id',
            'status',
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
        $data->orderBy($sortBy, $sort_direction);
        return $data->paginate(PAGINATION_COUNT_ADMIN);

    }
    public function getModelById(int $id)
    {
        return $this->getModel()::query()->where('id', $id)->first();
    }
    public function store($data)
    {
        return $this->getModel()::query()->create($data);
    }
    public function update($id, $data)
    {
        $slider = $this->getModel()::query()->find($id);
        if ($slider) {
            return $slider->update($data);
        }
        return false;
    }
    private function getModel() : Slider
    {
        return new Slider();
    }
}
