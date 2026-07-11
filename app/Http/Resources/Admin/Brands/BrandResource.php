<?php

namespace App\Http\Resources\Admin\Brands;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource
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
            'name' => [
                'ar' => $this->getTranslation('name','ar'),
                'en' => $this->getTranslation('name','en'),
            ],
            'status' => $this->status,
            'image' => $this->imageUrl,
        ];
    }
}
