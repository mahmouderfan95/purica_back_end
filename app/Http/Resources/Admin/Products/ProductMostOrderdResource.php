<?php

namespace App\Http\Resources\Admin\Products;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductMostOrderdResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                    => $this->id,
            'name'                  => $this->name,
            'image'                 => $this->image_url,
            'total_quantity_ordered'=> (int)$this->total_quantity_ordered,
            'total_orders'          => (int)$this->total_orders,
        ];
    }
}
