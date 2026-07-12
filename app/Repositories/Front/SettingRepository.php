<?php
namespace App\Repositories\Front;
use App\Models\Setting;

class SettingRepository
{
    public function getSettings()
    {
        return $this->getModel()::query()->first();
    }
    private function getModel() : Setting
    {
        return new Setting();
    }
}
