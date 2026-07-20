<?php

namespace App\Http\Resources\Front;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShippingCostResource extends JsonResource
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
            'shipping_company_id' => $this->shipping_company_id,
            'shippingCost' => $this->price
        ];
    }
}
