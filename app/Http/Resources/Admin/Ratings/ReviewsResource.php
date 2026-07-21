<?php

namespace App\Http\Resources\Admin\Ratings;

use App\Http\Resources\Admin\Products\ProductResource;
use App\Http\Resources\Front\Users\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewsResource extends JsonResource
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
            'rating' => $this->rating,
            'comment' => $this->comment,
            'product' => $this->whenLoaded('product',ProductResource::make($this->product)),
            'user' => $this->whenLoaded('user',UserResource::make($this->user)),
            'created_at' => $this->created_at
        ];
    }
}
