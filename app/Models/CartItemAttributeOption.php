<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItemAttributeOption extends Model
{
    use HasFactory;
    protected $fillable = [
        'cart_item_id',
        'attribute_option_id',
    ];
    public function cartItem() : BelongsTo
    {
        return $this->belongsTo(CartItem::class, 'cart_item_id');
    }
    public function attributeOption() : BelongsTo
    {
        return $this->belongsTo(AttributeOption::class, 'attribute_option_id');
    }
}
