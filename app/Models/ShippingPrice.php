<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShippingPrice extends Model
{
    use HasFactory;
    protected $fillable = [
        'shipping_company_id',
        'city_id',
        'region_id',
        'price'
    ];
    public function shippingCompany() : BelongsTo
    {
        return $this->belongsTo(ShippingCompany::class, 'shipping_company_id');
    }
    public function city() : BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id');
    }
    public function region() : BelongsTo
    {
        return $this->belongsTo(Region::class, 'region_id');
    }
}
