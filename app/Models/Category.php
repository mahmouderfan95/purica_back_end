<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use HasFactory,HasTranslations;
    public $translatable = ['name'];
    protected $fillable = [
      'name',
      'image',
      'banner_image',
      'status',
    ];
    public function products() : HasMany
    {
        return $this->hasMany(Product::class,'category_id');
    }
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function getImageUrlAttribute()
    {
        return $this->image == null ? asset('images/default.jpg') :asset('storage/uploads/categories/' . $this->image) ;
    }
    public function getBannerImageUrlAttribute()
    {
        return $this->banner_image == null ? asset('images/default.jpg') :asset('storage/uploads/categories/banners/' . $this->banner_image) ;
    }
}
