<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
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
            'id'                => $this->id,
            'gateway'           => $this->gateway,
            'order_id'          => $this->order_id,
            'amount'            => $this->amount,
            'description'       => $this->description,
            'reference'         => $this->reference,
            'kind'              => $this->kind,
            'status'            => $this->status,
            'error_code'        => $this->error_code,
            'payload'           => $this->payload,
            'user_id'           => $this->user_id,
            'reservation_id'    => $this->reservation_id,
        ];
    }
}
