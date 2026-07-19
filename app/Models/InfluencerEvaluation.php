<?php

namespace App\Models;

use App\Enums\GeneralStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfluencerEvaluation extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'image',
        'url',
        'status'
    ];
    public function scopeActive($query)
    {
        return $query->where('status', GeneralStatusEnum::ACTIVE);
    }
    public function getImageUrlAttribute()
    {
        return $this->image == null ? asset('images/default.jpg') :asset('storage/uploads/reviews/' . $this->image) ;
    }
}
