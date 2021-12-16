<?php

namespace App\Models;

use App\Models\Rating;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;

class Boat extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'price',
        'capacity',
        'coord',
        'published_at',
        'owner_id',
        'type_id',
        'available_days',
        'reserved_days'
    ];
    protected $appends = ['favourite'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'price' => 'decimal:2',
        'published_at' => 'timestamp',
        'owner_id' => 'integer',
        'type_id' => 'integer',
        'available_days'=>'array',
        'reserved_days'=>'array'
    ];


    public function reservations()
    {
        return $this->hasMany(\App\Models\Reservation::class);
    }

    public function owner()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function type()
    {
        return $this->belongsTo(\App\Models\Type::class);
    }

    public function galleries(){
        return $this->hasMany(Gallery::class);
    }

    // public function availables(){
    //     return $this->hasOne(\App\Models\Availavility::class);
    // }

    public function stats(){

        return $this->hasManyThrough(Rating::class, Reservation::class);

    }

    public function getRatingAttribute(){
        $rates=[];
        foreach ($this->stats as $rate) {
            array_push($rates, $rate->rate);
        }

        return $rates;
         return array_count_values($rates);
    }

    public function getFavouriteAttribute(){

        // Detectar el usuario logueado
        $user = auth('sanctum')->user();

        if(isset($user->preferences['favourites_boats'])) $favourites =  $user->preferences['favourites_boats'];

        $fav = false;

        if ($user) {
            if ($user->preferences) {
                if ($user->preferences['favourites_boats']) {
                    $favourites = $user->preferences['favourites_boats'];
                } else {
                    $favourites = [];
                }
            } else {
                $favourites = [];
            }

            if (in_array($this->id, $favourites))  ($fav = true);
        }

        return $fav;

    }

}
