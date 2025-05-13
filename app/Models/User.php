<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class User extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $hidden  = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
    ];
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    public function product(): HasMany{

        return $this->hasMany(Product::class);
    }

}
