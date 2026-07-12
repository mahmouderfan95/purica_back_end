<?php
namespace App\Repositories\Front;
use App\Models\Slider;

class SliderRepository
{
    public function getSliders()
    {
        return $this->getModel()::query()
            ->Active()
            ->Home()
            ->select('id', 'title', 'status','image','url','type','position')
            ->orderByDesc('id')
            ->take(4)
            ->get();
    }
    private function getModel() : Slider
    {
        return new Slider();
    }
}
