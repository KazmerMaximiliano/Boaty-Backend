<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    const ROLES = [
        'admin',
        'owner',
        'client',
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'address',
        'birthday',
        'email',
        'password',
        'photo',
        'roles',
        'preferences',
        'crypto_currency',
        'crypto_address'
    ];
    protected $appends = ['favourites'];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'roles' => 'array',
        'preferences' => 'array',
    ];

    public function providers() {
        return $this->hasMany(Provider::class, 'user_id', 'id');
    }

    public function favourites(){
        return $fav = json_decode($this->preferences);
    }

    public function getFavouritesAttribute(){
        if ($this->preferences) {
            if ($this->preferences['favourites_boats']) {
                return $this->preferences['favourites_boats'];
            } else {
                return [];
            }
        } else {
            return [];
        }
    }
}
