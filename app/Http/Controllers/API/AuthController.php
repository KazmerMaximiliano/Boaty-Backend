<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $attributes = $request->validate(
            [
                'first_name'       => 'required|string|max:255',
                'last_name'        => 'required|string|max:255',
                'phone'            => 'required|string|max:255',
                'address'          => 'required|string|max:255',
                'email'            => 'required|email|unique:users,email,null,id',
                'birthday'         => 'required|string|max:255',
                'password'         => 'required|string|min:6',
                'password_confirm' => 'required|string|min:6|same:password',
            ],
            [
                'email.unique'          => 'El valor del campo email ya está en uso.',
                'password.required'     => 'La contraseña es requerida',
                'password_confirm.same' => 'Las contraseñas deben coincidir',
            ]
        );

        if ($request->password == $request->password_confirm) {
            $attributes['password'] = bcrypt($attributes['password']);
            $attributes['roles']    = ['client'];
            $attributes['photo']    = '/img/users/noimage.png';

            $user = User::create($attributes);
            return $user;
        } else {
            return back()->withErrors([
                'Los datos ingresados no son correctos',
            ]);
        }
    }

    public function addOwnerRole(Request $request) {
        $user = $request->user();
        $user->roles = ['owner','client'];
        $user->save();

        return $user;
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return response()->json(['success' => true], 200);
        }

        return response()->json('Las credenciales no coinciden con nuestros registros', 401);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();
    }

    public function apiLogin(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return $user->createToken($user->first_name . ' ' . $user->last_name . ' - ' .date('l jS \of F Y h:i:s A'))->plainTextToken;
    }

    public function google(Request $request){
        $socialiteUser = Socialite::driver('google')->stateless()->userFromToken($request->token);

        $user = User::firstOrCreate(
            ['email' => $socialiteUser->email],
            [
                'email_verified_at' => now(),
                'first_name' => $socialiteUser->name,
                'last_name' => $socialiteUser->name,
                'photo' => '/img/users/noimage.png',
                'roles' => ['client'],
            ]
        );

        return $user->createToken($user->first_name . ' ' . $user->email . ' - ' .date('l jS \of F Y h:i:s A'))->plainTextToken;
    }

    public function facebook(Request $request){
        $socialiteUser = Socialite::driver('facebook')->stateless()->userFromToken($request->token);

        $user = User::firstOrCreate(
            ['email' => $socialiteUser->email],
            [
                'email_verified_at' => now(),
                'first_name' => $socialiteUser->name,
                'last_name' => $socialiteUser->name,
                'photo' => '/img/users/noimage.png',
                'roles' => ['client'],
            ]
        );

        return $user->createToken($user->first_name . ' ' . $user->email . ' - ' .date('l jS \of F Y h:i:s A'))->plainTextToken;
    }

    // WEB
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $socialiteUser = Socialite::driver('google')->user();

            $user = User::firstOrCreate(
                ['email' => $socialiteUser->email],
                [
                    'email_verified_at' => now(),
                    'first_name' => $socialiteUser->name,
                    'last_name' => $socialiteUser->name,
                    'photo' => '/img/users/noimage.png',
                    'roles' => ['client'],
                ]
            );

            return redirect()->intended('/');

        } catch (\Throwable $th) {
            return redirect()->route('/');
        }
    }

    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        try {
            $socialiteUser = Socialite::driver('facebook')->user();

            $user = User::firstOrCreate(
                ['email' => $socialiteUser->email],
                [
                    'email_verified_at' => now(),
                    'first_name' => $socialiteUser->name,
                    'last_name' => $socialiteUser->name,
                    'photo' => '/img/users/noimage.png',
                    'roles' => ['client'],
                ]
            );

            return redirect()->intended('/');

        } catch (\Throwable $th) {
            return redirect()->route('/');
        }
    }

    public function apiLogout(Request $request) {
        $user = $request->user();
        return $user->tokens()->delete();
    }
}
