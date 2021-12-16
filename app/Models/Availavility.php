<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Availavility extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'available_days',
        'reserved_days',
        'boat_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'available_days' => 'array',
        'reserved_days' => 'array',
        'boat_id' => 'integer',
    ];


    public function boat()
    {
        return $this->belongsTo(\App\Models\Boat::class);
    }
}
