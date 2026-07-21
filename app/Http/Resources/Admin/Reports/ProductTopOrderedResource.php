<?php

namespace App\Http\Resources\Admin\Reports;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductTopOrderedResource extends JsonResource
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
            'name' => $this->name,
            'image' => $this->imageUrl,
            'total_ordered' => $this->total_ordered,
            'peak_date' => $this->peak_date,
        ];
    }
}
