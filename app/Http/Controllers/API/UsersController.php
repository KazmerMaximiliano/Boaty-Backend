<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Traits\FotosTrait;
use App\Traits\RespondsJson;
use Illuminate\Http\Request;
use App\Traits\StripeTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    use RespondsJson, StripeTransaction;

    public function user()
    {
        $user = User::find(auth('sanctum')->id());
        return $user;
    }

    public function index(Request $request)
    {
        return User::orderBy('first_name')->get();
    }

    public function store(Request $request)
    {
        $attributes = $request->validate(
            [
                'first_name'  => 'required|string|max:255',
                'last_name'  => 'required|string|max:255',
                'phone'  => 'required|string|max:255',
                'address'  => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,null,id',
                'password' => 'required|string|min:6',
                'password_confirm' => 'required|string|min:6|same:password',
            ],
            [
                'email.unique' => 'El valor del campo email ya está en uso.',
                'password.required' => 'La contraseña es requerida',
                'password_confirm.same' => 'Las contraseñas deben coincidir',
            ]
        );

        if ($request->password == $request->password_confirm) {
            $attributes['password'] = bcrypt($attributes['password']);

            User::create($attributes);
        }
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $request->validate([
            'first_name'  => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'phone'  => 'required|string|max:255',
            'address'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'required|string|min:6',
            'password_confirm' => 'required|string|min:6|same:password',
        ]);

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->email = $request->email;

        if ($request->password) {
            if ($request->password == $request->password_confirm) {
                $user->password =  bcrypt($request->password);
            }
        }
        $user->save();
    }

    public function updateAccount(Request $request)
    {
        $user = User::find(auth('sanctum')->id());
        if ($request->get('photo')) {
            $data = new Request(['photo' => $request->get('photo'), 'newFoto' => $request->get('newFoto')]);
            $foto = FotosTrait::profileUpdatePhoto($data, 'usuarios', $user);
            $user->photo = $foto;
        }

        if ($request->current_password) {
            if (Hash::check($request->current_password, auth()->user()->password)) {

                $attributes = $request->validate([
                    'first_name'  => 'required|string|max:255',
                    'last_name'  => 'required|string|max:255',
                    'phone'  => 'required|string|max:255',
                    'address'  => 'required|string|max:255',
                    'password' => 'required|string|min:6',
                    'confirm_password' => 'required|same:password',
                    'email' => 'required|string|max:255|unique:users,email,' . $user->id,
                    'crypto_currency' => 'string|max:255',
                    'crypto_address'  => 'string|max:255',
                ]);

                $attributes['password'] =  bcrypt($attributes['password']);
                $user->update($request);

                return response()->json('ok', 200);
            } else {
                return response()->json('Contraseña Incorrecta', 401);
            }
        } else {
            $attributes = $request->validate([
                'first_name'  => 'required|string|max:255',
                'last_name'  => 'required|string|max:255',
                'phone'  => 'required|string|max:255',
                'address'  => 'required|string|max:255',
                'email' => 'required|string|max:255|unique:users,email,' . $user->id,
                'crypto_currency' => 'string|max:255',
                'crypto_address'  => 'string|max:255',
            ]);

            $user->update($attributes);

            return response()->json('ok', 200);
        }
    }

    public function destroy($id){
        if ($id > 1) {
            $user = User::find($id);
            $user->forceDelete();
        }
    }

    public function connectAccount() {
        $conectedUser = auth('sanctum')->user();
        $user = User::find($conectedUser->id);

        if($user->stripe_id === null){
            $res  = $this->connect($user);
            if ($res->id) {
                $user->stripe_id = $res->id;
                $user->save();
            } else {
                return response()->json(array(
                    'code'      =>  404,
                    'message'   =>  'Ha ocurrido un error'
                ), 404);
            }

        }

        $link  = $this->links($user);

        return $link->url;
    }

    public function setCryptoAddress(Request $request) {
        $conectedUser = auth('sanctum')->user();
        $user = User::find($conectedUser->id);

        $user->crypto_currency = $request->crypto_currency;
        $user->crypto_address = $request->crypto_address;

        $user->save();

        return response()->json(array(
            'code'      =>  200,
            'message'   =>  'Ok'
        ), 200);
    }

    public function getOwnerCryptoAdders() {
        $user = User::find(1);
        return response()->json([
            'address' => $user->crypto_address,
            'currency' => $user->crypto_currency
        ], 200);
    }

}

