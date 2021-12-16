<?php

namespace App\Http\Controllers\Api;

use App\Models\Rating;
use App\Models\Reservation;
use App\Traits\RespondsJson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\RatingResource;

class RatingController extends Controller
{
    use RespondsJson;

    public function store(Request $request)
    {
        $request->validate([
            'reservation_id' => 'required|exists:reservations,id',
            'rate'           => 'required',
        ]);

        $rating = DB::transaction(function () use ($request) {
            return Rating::updateOrCreate(
                [ 'reservation_id' => $request->reservation_id, ],
                [ 'rate'           => $request->rate, ]
            );
        });

        $reservation =  Reservation::find($request->reservation_id);
        $reservation->update([
            'status'        => 3
        ]);

        return $this->jsonResponse(__('crud.store.success'), new RatingResource($rating));
    }
}
