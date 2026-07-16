<?php

namespace App\Http\Resources\Admin\ShippingPrices;

use App\Http\Resources\Admin\Cities\CityResource;
use App\Http\Resources\Admin\Regions\RegionResource;
use App\Http\Resources\Admin\ShippingCompanies\ShippingCompanyResource;
use App\Http\Resources\Admin\ShippingCompanies\ShippingResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class   ShippingPriceResource extends JsonResource
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
          'shipping_company' => ShippingCompanyResource::make($this->whenLoaded('shippingCompany')),
          'city' => CityResource::make($this->whenLoaded('city')),
//          'region' => RegionResource::make($this->whenLoaded('region')),
          'price' => $this->price
        ];
    }
}
