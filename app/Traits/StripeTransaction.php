<?php

namespace App\Traits;

use App\Models\Boat;
use App\Models\User;
use App\Models\Reservation;
use App\Resolvers\PlatformResolver;


trait StripeTransaction
{
    public $paymentPlatform;

    public function __construct(PlatformResolver $paymentPlatformResolver)
    {
      $this->paymentPlatformResolver = $paymentPlatformResolver;
        $this->paymentPlatform = $this->paymentPlatformResolver
        ->resolveService(1);
    }

    public function generateCharge($amount,$currency,$token){
        $res  = $this->paymentPlatform->createCharge($amount,$currency,$token);
        return $res;
    }

    public function connect($user){
        $data['country']='ES';
        $data['email']= $user->email;
        return $this->paymentPlatform->connect($data);
    }

    public function links($user){
        $acc = $user->stripe_id;
        return $this->paymentPlatform->links($acc);
    }

    public function pay($reservation){
        // $reservation = Reservation::find($id);
        $boat =  Boat::find($reservation->boat_id);
        $owner = User::find($boat->owner_id);

        $amount = str_replace(".","",$reservation->amount);

        $data['amount']=$amount;
        $data['currency']= 'eur';
        $data['paymentMethod']= 'card';
        $data['acc'] = $owner->stripe_id;

        return $intent  = $this->paymentPlatform->createIntent($data);

    }

    public function confirm($transaction){

        return $intent  = $this->paymentPlatform->confirmPayment($transaction->reference);


    }

    public function chargeReservation($reservation_id, $token){
        $reservation = Reservation::find($reservation_id);
        $user = User::find($reservation->boat->owner->id);
        $data['amount'] = str_replace(".","",$reservation->amount);
        $data['currency']='eur';
        $data['token'] = $token;
        $data['description'] = 'Payment for reservation '.$reservation->id;
        $data['acc'] = $user->stripe_id;
        return $intent  = $this->paymentPlatform->handlePayment($data);
    }

    public function saveRequest($request){

        $data =[
            'country' =>$request->company['country'],
            'email' => $request->company['email'],
            'business_type'=>'individual',
            'city'=>$request->company['city'],
            'line1' =>$request->company['line1'],
            'zip_code'=>$request->company['zip_code'],
            'state'=>$request->company['state'],
            'name' =>$request->company['name'],
            'phone'=>$request->company['phone'],
            'tax_id'=>$request->company['tax_id'],

            'mcc'=>$request->bussiness['mcc'],
            'bussiness_name'=>$request->bussiness['name'],
            'support_phone'=>$request->bussiness['support_phone'],
            'support_email'=>$request->bussiness['support_email'],
            'url'=>$request->bussiness['url'],

            'ext_country'=>$request->external_account['country'],
            'ext_currency'=>$request->external_account['currency'],
            'account_holder_name'=>$request->external_account['account_holder_name'],
            'account_holder_type'=>$request->external_account['account_holder_type'],
            'routing_number'=>$request->external_account['routing_number'],
            'account_number'=>$request->external_account['account_number'],
        ];

        return $data;

    }

    public function stripeConnect($owner){


        // $stripe = new \Stripe\StripeClient('sk_test_lUdHmow6NxkuyqURgkdVpnmx00WWsmXD4s');
        $data = json_decode($owner->extras,true);


        try {
            $acc =$this->paymentPlatform->connect($data);
            $res = $this->paymentPlatform->links($acc->id);
            $user = User::find($owner->user->id);
            $user->update([
                'stripe_id'=>$acc->id,
            ]);

            $response = [
                'success' => true,
                'message' => 'OK',
                'data'    => $res,
            ];


        }
           catch (\Stripe\Exception\InvalidRequestException $e) {

                $response = [
                    'success' => false,
                    'message' => 'Error',
                    'data'    => $e->getError()->message,
                ];
          } catch (\Stripe\Exception\AuthenticationException $e) {
            // Authentication with Stripe's API failed
            // (maybe you changed API keys recently)
            return 'Api error';

          } catch (\Stripe\Exception\ApiConnectionException $e) {
            // Network communication with Stripe failed

            return 'Network';


          } catch (\Stripe\Exception\ApiErrorException $e) {
            // Display a very generic error to the user, and maybe send
            // yourself an email
            return response()->json($e);
            return 'User error';


          } catch (\Exception $e) {
            // Something else happened, completely unrelated to Stripe
            return response()->json($e);


          }


          return $response;
    }

}
