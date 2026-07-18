<?php

namespace App\Http\Resources\Admin\Orders;

use App\Http\Resources\Admin\AuthResource;
use App\Http\Resources\Admin\Cities\CityResource;
use App\Http\Resources\Admin\Coupons\CouponResource;
use App\Http\Resources\Admin\ShippingCompanies\ShippingCompanyResource;
use App\Http\Resources\Front\Orders\OrderItemResource;
use App\Http\Resources\Front\Users\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'total' => $this->total,
            'address' => $this->address,
            'payment_type' => $this->payment_type,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'items' => OrderItemResource::collection($this->whenLoaded('items')),
            'city' => CityResource::make($this->whenLoaded('city')),
//          'region' => RegionResource::make($this->whenLoaded('region')),
            'user' => UserResource::make($this->whenLoaded('user')),
            'shippingCompany' => ShippingCompanyResource::make($this->whenLoaded('shippingCompany')),
            'shipping_cost' => $this->shipping_cost,
            'createdBy' => AuthResource::make($this->whenLoaded('createdBy')),
            'coupon' => CouponResource::make($this->whenLoaded('coupon')),
            'discount' => $this->discount,
            'cancelled_at' => $this->cancelled_at,
            'cancel_reason' => $this->cancel_reason,
            'client_name' => $this->client_name ?? null,
            'client_phone' => $this->client_phone ?? null,
        ];
    }
}
