<?php
namespace App\Repositories\Admin;
use App\Models\Setting;

class SettingRepository
{
    public function getSettings()
    {
        return $this->getModel()::query()
            ->select(['id','site_name','site_description','site_logo',
                'site_phone','site_address',
                'facebook','instagram','whatsapp','tiktok','site_video'])
            ->latest()
            ->first();
    }
    private function getModel() : Setting
    {
        return new Setting();
    }
}
