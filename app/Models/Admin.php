<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use HasFactory,Notifiable,HasApiTokens,HasRoles;
    protected $fillable = [
      'name',
      'email',
      'password',
    ];
    public function orders() : HasMany
    {
        return $this->hasMany(Order::class,'created_by','id');
    }
}
