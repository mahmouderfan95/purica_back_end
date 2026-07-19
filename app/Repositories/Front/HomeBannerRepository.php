<?php
namespace App\Repositories\Front;
use App\Enums\GeneralStatusEnum;
use App\Models\HomeBanner;

class HomeBannerRepository
{
    public function getOffersBanners()
    {
        return $this->getModel()::query()
            ->select('id','title','button_text','button_link','image','status')
            ->whereStatus(GeneralStatusEnum::ACTIVE)
            ->take(6)
            ->latest()
            ->get();
    }
    private function getModel() : HomeBanner
    {
        return new HomeBanner();
    }
}
