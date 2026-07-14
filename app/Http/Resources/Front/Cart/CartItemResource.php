<?php

namespace App\Http\Resources\Front\Cart;

use App\Http\Resources\Front\Products\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
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
            'quantity' => $this->quantity,
            'price' => $this->price,
            'total' => $this->total,
            'selected_options' => $this->selected_options,
            'product' => ProductResource::make($this->whenLoaded('product')),
        ];
    }
}
