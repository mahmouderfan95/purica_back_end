<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    protected $fillable = [
        'site_name',
        'site_description',
        'site_logo',
        'site_address',
        'site_phone',
        'whatsapp',
        'facebook',
        'tiktok',
        'instagram',
    ];
    public function getSiteLogoUrlAttribute()
    {
        return $this->site_logo == null ? asset('images/default.jpg') :asset('storage/uploads/settings/' . $this->site_logo);
    }
}
