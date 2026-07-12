<?php

namespace App\Http\Resources\Admin\Settings;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'site_name' => $this->site_name,
            'site_description' => $this->site_description,
            'site_logo' => $this->SiteLogoUrl,
            'site_address' => $this->site_address,
            'site_phone' => $this->site_phone,
            'site_video' => $this->site_video,
            'whatsapp' => $this->whatsapp,
            'facebook' => $this->facebook,
            'tiktok' => $this->tiktok,
            'instagram' => $this->instagram,
        ];
    }
}
