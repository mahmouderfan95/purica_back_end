<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Product extends Model
{
    use HasFactory,HasTranslations;
    public $translatable = ['name','description'];
    protected $fillable = [
        'name',
        'image',
        'description',
        'slug',
        'price',
        'price_after_discount',
        'available_quantity',
        'status',
        'category_id',
        'brand_id',
        'discount_end_at'
    ];
    protected $appends = ['is_fav'];
    public function category() : BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function brand() : BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }
    public function attributeOptions() :BelongsToMany
    {
        return $this->belongsToMany(AttributeOption::class, 'product_attribute_options');
    }
    public function media() :HasMany
    {
        return $this->hasMany(ProductAttachment::class, 'product_id');
    }
    public function variants() :HasMany
    {
        return $this->hasMany(ProductVariants::class, 'product_id');
    }
    public function ratings() :HasMany
    {
        return $this->hasMany(Rating::class,'product_id');
    }
    public function favorites(): HasMany
    {
        return $this->hasMany(UserProductWhitelists::class, 'product_id');
    }
    public function getIsFavAttribute()
    {
        $user = auth('api')->user();

        if (!$user) {
            return false;
        }

        return $user->favorites()->where('product_id', $this->id)->exists();
    }
    public function averageRating(): float
    {
        return round($this->ratings()->avg('rating'), 1) ?? 0.0;
    }
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
    public function getImageUrlAttribute()
    {
        return $this->image == null ? asset('images/default.jpg') :asset('storage/uploads/products/' . $this->image) ;
    }
}
