<?php

namespace App\Traits;

use Illuminate\Support\Arr;


trait Transactionable
{

    //Generar transaccion para metodo de pago con tarjeta
    public function chargeCard($intent){


        return $reservation->moves()->create([
            'gateway'           =>'stripe',
            'order_id'          =>'1',
            'amount'            =>$this->amount,
            'description'       =>`Pago por reservacion del bote`. $this->boat->title,
            'reference'         => $intent->id,
            'kind'              =>'intent',
            'status'            => 1,
            'payload'           =>$intent,
            'user_id'           =>$this->client_id,
            'reservation_id'    =>$this->id,
        ]);


    }

    //Generar transaccion para metodo de pago en efectivo o transferencia
    public function chargePayment($charge){

        return $this->moves()->create([
            'user_id'       => user()->id,
            'charge_id'     => '123asd',
            'parent_id'     => null,
            'amount'        => $charge['amount'],
            'reference'     => $charge['reference'],
            'description'   => $charge['description'],
            'kind'          => 'charge',
            'status'        => 'paid',
            'error_code'    => null,
            'is_test'       => 1,
            'gateway'       => 'conekta',
            'last_four'     => 0,
            'brand'         => 0,
            'payload'       => 'cash',
        ]);

    }



}
