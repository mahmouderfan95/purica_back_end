<?php

namespace App\Http\Resources\Front\Cart;

use App\Http\Resources\Front\Users\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
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
            'guest_token' => $this->guest_token,
            'user' => UserResource::make($this->whenLoaded('user')),
            'total' => $this->total,
            'created_at' => $this->created_at,
            'items' => CartItemResource::collection($this->whenLoaded('items')),
        ];
    }
}
