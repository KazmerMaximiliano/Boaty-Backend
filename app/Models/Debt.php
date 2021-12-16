<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Debt extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'amount',
        'payment_method',
        'wallet_id',
        'payment_reference',
        'concept',
        'creditor',
        'debtor',
        'payload',
        'reservation_id',
    ];

    const STATUS = [
        'pending',
        'referenced',
        'paid',
        'cancelled',
    ];

    public function reservation(){
        return $this->belongsTo(\App\Models\Reservation::class);
    }
}
