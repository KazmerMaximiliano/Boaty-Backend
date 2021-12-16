<?php

namespace App\Http\Controllers\Api;

use App\Models\Boat;
use App\Traits\Available;
use App\Models\Reservation;
use App\Traits\RespondsJson;
use Illuminate\Http\Request;
use App\Traits\Transactionable;
use Illuminate\Validation\Rule;
use App\Traits\StripeTransaction;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\ReservationResource;
use App\Http\Resources\TransactionResource;

class ReservationController extends Controller
{
    use RespondsJson, Available, StripeTransaction, Transactionable;

    public function index(Request $request){
        $user_id = $request->user()->id;
        $reservations =  Reservation::where('client_id', $user_id)->get();

        return $this->jsonResponse(__('crud.action.success'), ReservationResource::collection($reservations));
    }

    public function store(Request $request)
    {
        $boat = Boat::find($request->boat_id);

        $request->validate([
            'boat_id'           => 'required|exists:boats,id',
            'amount'            => 'required',
            'client_id'         => 'required|exists:users,id',
            'reserved_days.*'   =>  ['required', Rule::in($boat->available_days)],
        ]);


        $reservation = DB::transaction(function () use ($request) {
            return Reservation::updateOrCreate(
                [
                    'id'      => $request->id,
                    'boat_id' => $request->boat_id,
                ],
                [
                    'amount'        => $request->amount,
                    'client_id'     => $request->client_id,
                    'reserved_days' => $request->reserved_days,
                    'status'        => 0
                ]
            );
        });

        $dates = $this->subDays($request->reserved_days,$boat);

        if(!isset($boat->reserved_days)){
            $reserved = $request->reserved_days;
        } else {
            $reserved = $this->addReservedDays($request->reserved_days,$boat);
        }

        $boat->update([
            'available_days'    => $dates,
            'reserved_days'     => $reserved
        ]);

        return $this->jsonResponse(__('crud.store.success'), new ReservationResource($reservation));

    }

    public function show(Reservation $reservation)
    {

        return $this->jsonResponse(__('crud.store.success'), new ReservationResource($reservation));
    }

    public function cancel(Reservation $reservation)
    {
        if($reservation->status < 2){

            $reservation->update([
                'status' => 4
            ]);

            $boat = Boat::find($reservation->boat_id);

            $availables = $this->addDays($reservation->reserved_days,$boat);
            $reserved [] = [];
            $reserved =$this->subReservedDays($reservation->reserved_days,$boat);

            $boat->update([
                'available_days'   =>$availables,
                'reserved_days'    =>$reserved
            ]);

            return $this->jsonResponse(__('crud.cancel.success'), new ReservationResource($reservation));
        } else {
            return $this->jsonErrorResponse(__('crud.cancel.error'), new ReservationResource($reservation));
        }
    }


    public function payReservation(Request $request){

        $reservation = Reservation::find($request->reservation_id);

        $intent = $this->pay($reservation);

        $trans = $reservation->moves()->create([
            'gateway'           =>'stripe',
            'order_id'          =>'1',
            'amount'            => $intent->amount,
            'description'       =>'Pago por reservacion del bote',
            'reference'         => $intent->id,
            'kind'              =>'intent',
            'status'            => 1,
            'payload'           =>json_encode($intent),
            'user_id'           =>$reservation->client_id,
            'reservation_id'    =>$reservation->id,
        ]);

        return $this->jsonResponse(__('crud.store.success'), new TransactionResource($trans));
    }

    // public function getPaymentInfo($id) {
    //     $stripe = new \Stripe\StripeClient(
    //         'pk_test_51JQAziBVyxRuQ178phdrUkXuqbfTfOtBMi32O04SPOXJ6YZZj7ZFqnQEHtGporBuSQEjZI55WDi8Tc4LkgNwcF5400ngW98Z6C'
    //     );

    //     $cardToken = $stripe->tokens->create([
    //         'card' => [
    //             'number' => '4242424242424242',
    //             'exp_month' => 9,
    //             'exp_year' => 2022,
    //             'cvc' => '314',
    //         ],
    //     ]);

    //     return $cardToken;
    // }

    public function confirmPayment(Request $request){

      /*   ================================================
        Aqui se debe enviar la reservacion y el token de la tarjeta
        Se creae el charge directamente a la cuenta conectada
        se crea la transaccion y se paga la reservacion
        ==================================================== */

        $stripe = new \Stripe\StripeClient(
           'sk_test_51JQAziBVyxRuQ178tEMXZSskIscpuyFnBiVWMh7EjItVXbnU0kdRNIUmIlAUN85JrGMHolTm9aTyYy5TnAHTgLhh00XYLiSanB'
        );

        $cardToken = $stripe->tokens->create([
            'card' => [
                'number' => $request->card_number,
                'exp_month' => $request->exp_month,
                'exp_year' => $request->exp_year,
                'cvc' => $request->cvc,
            ],
        ]);

        $reservation =  Reservation::find($request->reservation_id);

        $intent = $this->chargeReservation($reservation->id, $cardToken->id);

        if($intent->paid === true){
            $trans = $reservation->moves()->create([
                'gateway'           => 'stripe',
                'order_id'          => '1',
                'amount'            => $reservation->amount,
                'description'       => `Pago por reservacion del bote`. $reservation->boat->title,
                'reference'         => $intent->id,
                'kind'              => 'paid',
                'status'            => 2,
                'payload'           => json_encode($intent),
                'user_id'           => $reservation->client_id,
                'reservation_id'    => $reservation->id,
            ]);

            $reservation->update([
                'status'        => 2
            ]);
        }


        return $this->jsonResponse(__('crud.store.success'), new TransactionResource($trans));
    }
}
