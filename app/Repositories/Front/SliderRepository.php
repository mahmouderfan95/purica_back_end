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
    public function getSliderReviewsPage($slug)
    {
        return $this->getModel()::query()
            ->select('id', 'title', 'status','image','url','type','position','page_slug')
            ->Page($slug)
            ->first();
    }
    private function getModel() : Slider
    {
        return new Slider();
    }
}
