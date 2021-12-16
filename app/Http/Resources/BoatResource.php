<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BoatResource extends JsonResource
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
            'title'         => $this->title,
            'description'   => $this->description,
            'price'         => $this->price,
            'capacity'      => $this->capacity,
            'coords'        => $this->coords,
            'gallery'       => GalleryResource::collection($this->galleries),
            'type_id'       => $this->type_id,
            'owner_id'      => $this->owner_id,
            'availables'    => $this->available_days,
            'reserved'      => $this->reserved_days,
            'rating'        => $this->rating,
            'favourite'     => $this->favourite
        ];
    }
}
