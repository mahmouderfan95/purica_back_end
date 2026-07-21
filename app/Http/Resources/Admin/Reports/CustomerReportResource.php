<?php

namespace App\Http\Resources\Admin\Reports;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'email'         => $this->email,
            'phone'         => $this->phone,
            'total_orders'  => $this->total_orders ?? 0,
//            'total_revenue' => (float) $this->total_revenue ?? 0,
            'peak_period'   => $this->peak_period ?? null,
        ];
    }
}
