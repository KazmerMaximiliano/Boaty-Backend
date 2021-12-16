<?php

namespace App\Traits;

use App\Models\Gallery;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;


trait FotosTrait
{
    public static function profileUpdatePhoto($request, $ubicacion, $objeto)
    {
        $carpeta = public_path() . '/img/' . $ubicacion . '/';
        if (!file_exists($carpeta)) {
            mkdir($carpeta, 0777, true);
        }

        $eliminar = $objeto->photo;
        if (file_exists($eliminar)) {
            @unlink($eliminar);
        }

        $image = $request->get('photo');
        $name = time() . '.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
        Image::make($request->get('photo'))->save(public_path('img/' . $ubicacion . '/') . $name);
        return '/img/' . $ubicacion . '/' . $name;
    }

    public function boatUpdatePhotos($request)
    {
        $photos = $request->file('photos');
        foreach ($photos as $photo) {
            if (file_exists($photo->path)) {
                @unlink($photo->path);
                $photo->delete();
            }

            $var = date_create();
            $time = date_format($var, 'dmYHis');
            $imageName ="/img/boats/". $time . '-' . $photo->getClientOriginalName();
            Image::make($photo)->save(public_path() . $imageName);
            $res[] = $imageName;
        }

        return $res;
    }

    public function boatStorePhotos($request)
    {
        $photos = json_decode($request->photos);

        foreach($photos as $file)
        {            
            $img_url = "boat-".$this->generateRandomString().$this->getOriginalExtension($file);
            $path = public_path().'/img/boats/' . $img_url;
            Image::make(base64_decode($file))->save($path); 

            $res[] = '/img/boats/' . $img_url;
        }

        return $res;
    }

    static function getOriginalExtension($base64String) {
        switch ($base64String[0]) {
            case '/':
                return '.jpg';
                break;
            case 'i':
                return '.png';
                break;
            case 'R':
                return '.gif';
                break;
            case 'U':
                return '.webp';
                break;
            default:
                return '.png';
                break;
        }
    }

    static function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
