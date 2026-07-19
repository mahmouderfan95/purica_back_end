<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class HomeBanner extends Model
{
    use HasFactory,HasTranslations;
    public $translatable = ['title','button_text'];
    protected $fillable = [
        'title',
        'button_text',
        'image',
        'button_link',
        'sort',
        'status',
    ];
    public function getImageUrlAttribute()
    {
        return $this->image == null ? asset('images/default.jpg') :asset('storage/uploads/home_banners/' . $this->image) ;
    }
}
