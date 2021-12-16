<?php

namespace App\Http\Controllers\Api;

use App\Traits\RespondsJson;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;

class PreferenceController extends Controller
{
    use RespondsJson;

    public function favourite(Request $request)
    {
        $user = $request->user();
        $fav  = array();

        if(isset($user->preferences['favourites_boats'])){
            $fav = $user->preferences['favourites_boats'];
        }

        array_push($fav, $request->favourites_boat);
        
        $fav                            = array_unique($fav);
        $preference['favourites_boats'] = $fav;
        $user->preferences              = $preference;

        $user->save();

        return $this->jsonResponse(__('crud.store.success'), new UserResource($user));
    }

    public function unfavourite(Request $request)
    {
        $user  = $request->user();
        $unfav =  array();

        if(isset($user->preferences['favourites_boats'])){
            $unfav = $user->preferences['favourites_boats'];
        }

        if (($key =  array_search($request->unfavourite_boat,$unfav)) !== FALSE) {
            unset($unfav[$key]);
        }

        $res =  array();
        
        foreach ($unfav as $key => $value) {
           $res[] = $value;
        }

        $preference['favourites_boats'] = $res;
        $user->preferences              = $preference;
        
        $user->save();
        
        return $this->jsonResponse(__('crud.store.success'), new UserResource($user));
    }



}
