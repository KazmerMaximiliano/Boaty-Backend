<?php

namespace App\Http\Controllers\Api;

use App\Models\Boat;
use App\Traits\Available;
use App\Models\Availavility;
use App\Traits\RespondsJson;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\BoatResource;
use App\Http\Resources\AvailavilityResource;

class AvailavilityController extends Controller
{
    use RespondsJson, Available;

    public function store(Request $request)
    {
        $request->validate([
            'dates'   => 'required|array|min:1',
            'boat_id' => 'required|exists:boats,id',
        ]);

        $boat = Boat::find($request->boat_id);

        if(!isset($boat->available_days)){
            $dates = $request->dates;
        }else{
            $dates = $this->addDays($request->dates,$boat);
        }

        $boat->update([
            'available_days'    =>$dates
        ]);

        return $this->jsonResponse(__('crud.action.success'), new BoatResource($boat));

    }

    public function reserveDays(Request $request, Availavility $availavility)
    {
        $this->subDays($request->dates,$availavility);

        return $this->jsonResponse(__('crud.store.success'), new AvailavilityResource($availavility));
    }

    public function rearrangeDays(Request $request, Boat $boat)
    {
        return $this->addDays($request->dates,$boat);

        return $this->jsonResponse(__('crud.action.success'), new BoatResource($boat));
    }

    public function updateAvailavilities(Request $request, Boat $boat){

        $boat->update([
            'available_days'    =>$request->dates
        ]);

        return $this->jsonResponse(__('crud.action.success'), new BoatResource($boat));
    }
}
