<?php

namespace App\Http\Controllers\Api;

use App\Models\Boat;
use App\Models\Debt;
use App\Models\User;
use App\Models\Reservation;
use App\Traits\RespondsJson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\DebtResource;
use App\Http\Resources\TransactionResource;

class PaymentController extends Controller
{
    use RespondsJson;

    public function getOwnerWallet($id) {
        $reservation = Reservation::find($id);
        $boat = Boat::find($reservation->boat_id);
        $user = User::find($boat->owner_id);

        return $user->crypto_address;
    }

    public function getDebts() {
        $user = auth()->user();

        if (in_array("admin", $user->roles) || in_array("owner", $user->roles)) {
            $debts = Debt::where('creditor', $user->id)->get();
        } else {
            $debts = Debt::where('debtor', $user->id)->get();
        }

        foreach ($debts as $debt) {
            $debt->creditor = User::find($debt->creditor);
            $debt->debtor = User::find($debt->debtor);
        }

        return $debts;
    }

    public function payOffline(Request $request){

        $request->validate([
            'wallet_id'         => 'required',
            'reservation_id'    => 'required',
            'payment_method'    => 'required',
        ]);
        // Aqui se crea la deuda del cliente con boaty
        $debt = DB::transaction(function () use ($request) {
            $reservation = Reservation::find($request->reservation_id);
            return Debt::create([
                'status'            => 0,
                'amount'            => $reservation->amount,
                'payment_method'    => $request->payment_method,
                'wallet_id'         => $request->wallet_id,
                'concept'           => 'Pago a Boaty por reservación'.$reservation->id,
                'creditor'          => 1,
                'debtor'            => $reservation->client_id,
                'reservation_id'    => $reservation->id,
            ]);
        });

        return $this->jsonResponse(__('crud.action.success'), new DebtResource($debt));
    }

    public function confirmPaymentOffline(Request $request) {
        $debt = Debt::find($request->id);
        $reservation = Reservation::find($debt->reservation_id);

        $reservation->update([
            'status'        => 3,
        ]);

        $debt->update([
            'status'        => 2,
        ]);

        $trans = $reservation->moves()->create([
            'gateway'           => $debt->payment_method,
            'order_id'          =>'1',
            'amount'            => $debt->amount,
            'description'       =>`Pago a Dueños por reservación `. $reservation->boat->title,
            'reference'         => $debt->payment_reference,
            'kind'              =>'paid',
            'status'            => 2,
            'payload'           =>json_encode($debt),
            'user_id'           =>$reservation->client_id,
            'reservation_id'    =>$reservation->id,
        ]);

        $debt = DB::transaction(function () use ($debt) {
            $reservation = Reservation::find($debt->reservation_id);
            return Debt::create([
                'status'            => 0,
                'amount'            => ($reservation->amount) - ($reservation->amount)*0.2,
                'payment_method'    => $debt->payment_method,
                'wallet_id'         => $debt->wallet_id,
                'concept'           => 'Pago por reservacion del bote '.$reservation->id,
                'creditor'          => $reservation->boat->owner_id,
                'debtor'            => 1,
                'reservation_id'    => $reservation->id,
            ]);
        });

        return $this->jsonResponse(__('crud.store.success'), new TransactionResource($trans));
    }

    public function setReference(Request $request){
        $request->validate([
            'debt_id'                  => 'required',
            'payment_reference'        => 'required',
        ]);
        $debt =  Debt::find($request->debt_id);
        $debt->update([
            'status'                => 1,
            'payment_reference'     => $request->payment_reference,
        ]);

        return $debt;
    }

    public function confirmPayment(Debt $debt){
        $reservation = Reservation::find($debt->reservation_id);
        $debt->update([
            'status'                => 2,
        ]);
        $reservation->update([
            'status'        => 3,
        ]);
        $trans = $reservation->moves()->create([
            'gateway'           => $debt->payment_method,
            'order_id'          =>'1',
            'amount'            => $debt->amount,
            'description'       =>`Pago a Dueños por reservación `. $reservation->boat->title,
            'reference'         => $debt->payment_reference,
            'kind'              =>'paid',
            'status'            => 2,
            'payload'           =>json_encode($debt),
            'user_id'           =>$reservation->client_id,
            'reservation_id'    =>$reservation->id,
        ]);

        $debt = DB::transaction(function () use ($debt) {
            $reservation = Reservation::find($debt->reservation_id);
            return Debt::create([
                'status'            => 0,
                'amount'            => ($reservation->amount) - ($reservation->amount)*0.2,
                'payment_method'    => $debt->payment_method,
                'wallet_id'         => $debt->wallet_id,
                'concept'           => 'Pago por reservacion del bote '.$reservation->id,
                'creditor'          => $reservation->boat->owner_id,
                'debtor'            => 1,
                'reservation_id'    => $reservation->id,
            ]);
        });

        return $this->jsonResponse(__('crud.store.success'), new TransactionResource($trans));
    }
}
