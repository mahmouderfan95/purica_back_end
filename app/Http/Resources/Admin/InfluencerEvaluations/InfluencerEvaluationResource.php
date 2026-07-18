<?php

namespace App\Http\Resources\Admin\InfluencerEvaluations;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InfluencerEvaluationResource extends JsonResource
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
            'url' => $this->url,
            'created_at' => $this->created_at
        ];
    }
}
