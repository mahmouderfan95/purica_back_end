<?php

namespace App\Http\Resources\Admin\HomeBanners;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HomeBannerResource extends JsonResource
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
            'title' => [
                'ar' => $this->getTranslation('title','ar'),
                'en' => $this->getTranslation('title','en'),
            ],
            'button_text' => [
                'ar' => $this->getTranslation('button_text','ar'),
                'en' => $this->getTranslation('button_text','en'),
            ],
            'button_link' => $this->button_link,
            'sort' => $this->sort,
            'status' => $this->status,
            'image' => $this->imageUrl,
        ];
    }
}
