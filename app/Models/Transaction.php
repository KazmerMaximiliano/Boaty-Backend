<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $guarded=[];

    // public function reservation(){
    //     return $this->belongsTo(Reservation::class);
    // }
    public function movable(){
        return $this->morphTo();
    }
}
