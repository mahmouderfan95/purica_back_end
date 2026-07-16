<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShippingCompany extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'api_key',
        'email',
        'phone',
        'website',
        'is_default',
    ];
    public function prices() : HasMany
    {
        return $this->hasMany(ShippingPrice::class, 'shipping_company_id');
    }
}
