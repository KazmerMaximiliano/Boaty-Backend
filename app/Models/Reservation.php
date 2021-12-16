<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    const STATUS = [
        'pending',
        'reserved',
        'agended',
        'completed',
        'cancelled',
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'amount',
        'status',
        'client_id',
        'boat_id',
        'reserved_days'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'            =>'integer',
        'amount'        =>'decimal:2',
        'status'        =>'integer',
        'client_id'     =>'integer',
        'boat_id'       =>'integer',
        'reserved_days' =>'array'
    ];


    public function boat()
    {
        return $this->belongsTo(\App\Models\Boat::class);
    }

    public function client()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function debts(){
        return $this->hasMany(\App\Models\Debt::class);
    }

    public function moves()
    {
        return $this->morphMany(\App\Models\Transaction::class, 'movable');
    }
}
