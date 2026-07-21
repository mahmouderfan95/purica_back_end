<?php

namespace App\Http\Resources\Admin\Reports;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'name'           => $this->name,
            'email'          => $this->email,
            'orders_count'   => $this->orders_count ?? 0,
            'total_revenue'  => $this->total_revenue ?? 0.00,
        ];
    }
}
