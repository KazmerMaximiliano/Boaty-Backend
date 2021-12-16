<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\StripeClient;
use Illuminate\Http\Request;
use App\Traits\ConsumesExternalServices;

class StripeService
{
    use ConsumesExternalServices;

    protected $key;

    protected $secret;

    protected $baseUri;

      public function __construct()
    {
        $this->baseUri = config('services.stripe.base_uri');
        $this->key = config('services.stripe.key');
        $this->secret = config('services.stripe.secret');

    }

    public function resolveAuthorization(&$queryParams, &$formParams, &$headers)
    {
        $headers['Authorization'] = $this->resolveAccessToken();
    }

    public function decodeResponse($response)
    {
        return json_decode($response);
    }

    public function resolveAccessToken()
    {
        return "Bearer {$this->secret}";
    }

    public function handlePayment($data)
    {

        $stripe = new StripeClient(
            'sk_test_51JQAziBVyxRuQ178tEMXZSskIscpuyFnBiVWMh7EjItVXbnU0kdRNIUmIlAUN85JrGMHolTm9aTyYy5TnAHTgLhh00XYLiSanB'
          );
          return $stripe->charges->create([
            'amount' => $data['amount'],
            'currency' => $data['currency'],
            "source" => $data['token'],
            'description' => $data['description'],
          ], ['stripe_account' => $data['acc']]);


    }

    public function createCharge(){
        return $this->makeRequest(
            'POST',
            '/v1/charges',
            [],
            [
                'payment_method_types' => ['card'],
                'amount' => 1000,
                'currency' => 'usd',
                'transfer_data' => [
                  'destination' => 'acct_1JNiwY2ecgGCc3Cy',
                ],
            ],
        );

    }


    public function createIntent($data)
    {

        return $this->makeRequest(
            'POST',
            '/v1/payment_intents',
            [],
            // TODO: Pasar metodo de pago
            [
                'payment_method_types' => ['card'],
                'amount' => $data['amount'],
                'currency' => $data['currency'],
                'transfer_data' => [
                  'destination' => $data['acc'],
                ],
                'application_fee_amount'=>20
            ],
        );
    }

    public function confirmPayment($paymentIntentId)
    {

        return $this->makeRequest(
            'POST',
            '/v1/payment_intents/'.$paymentIntentId.'/confirm',
            [],
            []
        );
    }

    public function createCustomer($name, $email, $paymentMethod)
    {
        return $this->makeRequest(
            'POST',
            '/v1/customers',
            [],
            [
                'name' => $name,
                'email' => $email,
                'payment_method' => $paymentMethod,
            ],
        );
    }


    public function links($acc){
        return $this->makeRequest(
            'POST',
            '/v1/account_links',
            [],
            [
                'account' => $acc,
                'refresh_url' => 'http://localhost:8000/admin/',
                'return_url' => 'http://localhost:8000/admin/',
                'type' => 'account_onboarding',
            ],
        );
    }


    // Este connect es mas completo

    public function connect($data){
        return $this->makeRequest(
            'POST',
            '/v1/accounts',
            [],
            [
                'type' => 'standard',
                'country' =>$data['country'],
                'email' => $data['email'],
                'business_type'=>'individual',
            ]
            // [
            //     'type' => 'standard',
            //     'country' =>$data['country'],
            //     'email' => $data['email'],
            //     'business_type'=> 'individual',
            //     'company' =>
            //     [
            //         'address' => [
            //             'city'=>$data['city'],
            //             'country'=>$data['country'],
            //             'line1' =>$data['line1'],
            //             'postal_code'=>$data['zip_code'],
            //             'state'=>$data['state'],
            //         ],
            //         'name' =>$data['name'],
            //         'phone'=>$data['phone'],
            //         'tax_id'=>$data['tax_id']
            //     ],
            //     'business_profile'=>[
            //         'mcc'=>$data['mcc'],
            //         'name'=>$data['bussiness_name'],
            //         'support_phone'=>$data['support_phone'],
            //         'support_email'=>$data['support_email'],
            //         'url'=>$data['url'],
            //     ],
            //     'external_account' => [
            //         'object' =>'bank_account',
            //         'country'=>$data['ext_country'],
            //         'currency'=>$data['ext_currency'],
            //         'account_holder_name'=>$data['account_holder_name'],
            //         'account_holder_type'=>$data['account_holder_type'],
            //         'account_number'=>$data['account_number'],
            //     ],
            // ]
        );
    }

}
