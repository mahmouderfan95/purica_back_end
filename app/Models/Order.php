<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'user_id',
        'total',
        'status',
        'payment_type',
        'notes',
        'address',
        'country_id',
        'city_id',
        'region_id',
        'shipping_company_id',
        'shipping_cost',
        'created_by',
        'discount',
        'coupon_id',
        'cancel_reason',
        'cancelled_at',
        'addition_type',
//        'client_name',
//        'client_phone',
    ];
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function items() : HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }
    public function country() : BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
    public function city() : BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id');
    }
    public function region() : BelongsTo
    {
        return $this->belongsTo(Region::class, 'region_id');
    }
    public function shippingCompany() : BelongsTo
    {
        return $this->belongsTo(ShippingCompany::class, 'shipping_company_id');
    }
    public function createdBy() : BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }
    public function coupon() : BelongsTo
    {
        return $this->belongsTo(Coupon::class, 'coupon_id');
    }
}
