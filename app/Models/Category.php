<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use HasFactory,HasTranslations;
    public $translatable = ['name'];
    protected $fillable = [
      'name',
      'image',
      'status',
    ];
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function getImageUrlAttribute()
    {
        return $this->image == null ? asset('images/default.jpg') :asset('storage/uploads/categories/' . $this->image) ;
    }
}
