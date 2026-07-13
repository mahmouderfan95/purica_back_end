<?php

namespace App\Http\Resources\Front\Products;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SingleProductResource extends JsonResource
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
            'description' => [
                'ar' => $this->getTranslation('description','ar'),
                'en' => $this->getTranslation('description','en'),
            ],
            'status' => $this->status,
            'image' => $this->imageUrl,
            'slug' => $this->slug,
            'price' => $this->price,
            'price_after_discount' => $this->price_after_discount,
        ];
    }
}
