<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'first_name'    => $this->first_name,
            'last_name'     => $this->last_name,
            'email'         => $this->email,
            'phone'         => $this->phone,
            'address'       => $this->address,
            'photo'         => $this->photo,
            'completed_at'  => $this->completed_at,
            'roles'         => $this->roles,
            'preferences'   => $this->preferences,
            'stripe_id'     => $this->stripe_id,
            'favourites'    => $this->favourites,

        ];
    }
}
