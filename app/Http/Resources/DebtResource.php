<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DebtResource extends JsonResource
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
            'id'                        => $this->id,
            'status'                    => $this->status,
            'amount'                    => $this->amount,
            'payment_method'            => $this->payment_method,
            'wallet_id'                 => $this->wallet_id,
            'payment_reference'         => $this->payment_reference,
            'concept'                   => $this->concept,
            'creditor'                  => $this->creditor,
            'debtor'                    => $this->debtor,
            'payload'                   => $this->payload,
            'reservation_id'            => $this->reservation_id
        ];
    }
}
