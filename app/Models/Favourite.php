<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favourite extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'client_id',
        'boat_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'client_id' => 'integer',
        'boat_id' => 'integer',
    ];


    public function client()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function boat()
    {
        return $this->belongsTo(\App\Models\Boat::class);
    }
}
