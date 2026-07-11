<?php

namespace App\Models;

use App\Enums\SliderTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Slider extends Model
{
    use HasFactory,HasTranslations;
    public $translatable = ['title','description'];
    protected $fillable = [
      'title',
      'description',
      'image',
      'url',
      'status',
      'type',
      'page_slug',
      'position'
    ];
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeHome($query)
    {
        return $query->where('type', SliderTypeEnum::HOME);
    }
    public function scopePage($query, $slug)
    {
        return $query->where('type', SliderTypeEnum::PAGE)
            ->where('page_slug', $slug);
    }
    public function getImageUrlAttribute()
    {
        return $this->image == null ? asset('images/default.jpg') :asset('storage/uploads/sliders/' . $this->image) ;
    }
}
