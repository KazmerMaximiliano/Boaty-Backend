<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Boat;
use App\Traits\FotosTrait;
use App\Traits\RespondsJson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\BoatResource;

class BoatController extends Controller
{
    use RespondsJson, FotosTrait;

    public function index($search = null)
    {
        
        if ($search) {
            $boats =  Boat::where('title', 'LIKE', "%{$search}%")
                ->orWhere('description', 'LIKE', "%{$search}%")
                ->get();
        } else {
            $boats =  Boat::all();
        }

        return $this->jsonResponse(__('crud.action.success'), BoatResource::collection($boats));        


    }

    public function boatsByOwner(Request $request)
    {
        $user       = $request->user();
        $boats      =  Boat::where('owner_id', $user->id)->get();
        return $this->jsonResponse(__('crud.action.success'), BoatResource::collection($boats));
    }

    public function show(Boat $boat)
    {
        $boat =  Boat::with('stats')->find($boat->id);

        return $this->jsonResponse(__('crud.action.success'), new BoatResource($boat));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|min:5',
            'description' => 'required|min:5',
            'price'       => 'required|numeric',
            'capacity'    => 'required|numeric',
            'coord'       => 'required',
            'owner_id'    => 'required|exists:users,id',
            'type_id'     => 'required|exists:types,id',
        ]);

        $boat = DB::transaction(function () use ($request) {
            return Boat::create([
                'title'       => $request->title,
                'description' => $request->description,
                'price'       => $request->price,
                'capacity'    => $request->capacity,
                'coord'       => $request->coord,
                'owner_id'    => $request->owner_id,
                'type_id'     => $request->type_id,
                'published'   => Carbon::today(),

            ]);
        });

        if($request->has('photos')){
            $paths = collect($this->boatStorePhotos($request));

            foreach ($paths as $path) {
                $boat->galleries()->create([
                    'path' => $path,
                ]);
            }
        }


        return $this->jsonResponse(__('crud.action.success'), new BoatResource($boat));
    }

    public function update(Request $request, Boat $boat)
    {
        $request->validate([
            'description' => 'required|min:5',
            'price'       => 'required|numeric',
            'capacity'    => 'required|numeric',
            'coord'       => 'required',
        ]);

        $boat->update([
            'description'  => $request->description,
            'price'        => $request->price,
            'capacity'     => $request->capacity,
            'coord'        => $request->coord,
            'published_at' => $request->published_at,
        ]);

        $paths = collect($this->boatUpdatePhotos($request, $boat));

        if($paths) {
            foreach ($paths as $path) {
                $boat->galleries()->create([
                    'path' => $path,
                ]);
            }
        }

        return $this->jsonResponse(__('crud.action.success'), new BoatResource($boat));
    }

    public function favouriteBoats(Request $request)
    {
        $user       = $request->user();
        $favourites = $user->preferences['favourites_boats'];
        $boats      = Boat::whereIn('id', $favourites)->get();

        return $this->jsonResponse(__('crud.action.success'), BoatResource::collection($boats));
    }


}
