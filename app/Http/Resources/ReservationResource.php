<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id'            => $this->id,
            'amount'        => $this->amount,
            'status'        => $this->status,
            'client_id'     => $this->client_id,
            'boat_id'       => $this->boat_id,
            'reserved_days' => $this->reserved_days,
            'moves'         => $this->moves
        ];
    }
}
