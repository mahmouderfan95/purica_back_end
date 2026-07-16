<?php

namespace App\Http\Resources\Admin\Cities;

use App\Http\Resources\Admin\Countries\CountryResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
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
            'country' => CountryResource::make($this->whenLoaded('country')),
        ];
    }
}
