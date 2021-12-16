<?php

namespace App\Traits;

use App\Models\Boat;

trait Available
{

    public function addDays($dates,$boat){
        $availables = $boat->available_days;
        $new = array_merge($availables,$dates);
        $uniques = array_unique($new);
        return $uniques;

    }

    public function subDays($dates,$boat){

        $availables = $boat->available_days;

        foreach ($dates as $day) {
            if (($key =  array_search($day,$availables)) !== FALSE) {
                unset($availables[$key]);
            }
        }
        $new = [];
        foreach ($availables as $key=>$value) {
               $new [] =  $value;
        }
        return $new;
    }

    public function addReservedDays($dates,$boat){
        $reserved = $boat->reserved_days;
        $new = array_merge($reserved,$dates);
        $uniques = array_unique($new);
        return $uniques;

    }

    public function subReservedDays($dates, $boat){

        $reserved = $boat->reserved_days;

        foreach ($dates as $day) {
            if (($key =  array_search($day,$reserved)) !== FALSE) {
                unset($reserved[$key]);
            }
        }
        $new [] = 0;
        foreach ($reserved as $key=>$value) {
               $new [] =  $value;
        }
        unset($new[0]);
        return $new;

    }


    // public function subDays($dates, $boat_id){

    //     $boat =Boat::find($boat_id);
    //     $availables = $boat->available_days;
    //     $reserved []=null;
    //     foreach ($dates as $day) {
    //         if (($key =  array_search($day,$availables)) !== FALSE) {
    //             unset($availables[$key]);
    //             array_push($reserved,$key);
    //         }
    //     }

    //     foreach ($availables as $key=>$value) {
    //        $new [] =  $value;
    //     }
    //     unset($reserved[0]);

    //     if(!isset($new)){
    //        $boat->update([
    //         'available_days' => [],
    //         'reserved_days' => $reserved
    //         ]);
    //     }else{
    //            $boat->update([
    //                 'available_days' => $new,
    //                 'reserved_days' => $reserved
    //             ]);
    //     }

    // }

    // public function addDays($dates, $boat_id){
    //     $boat =Boat::find($boat_id);
    //     $res = array_merge($boat->available_days,$dates);
    //     $uniques = array_unique($res);

    //     foreach ($uniques as $key => $value) {
    //        $new []= $value;
    //     }
    //     $boat->update([
    //         'available_days' => $new,
    //     ]);
    // }

    // public function cancelDays($dates,$boat_id){
    //     $boat =Boat::find($boat_id);
    //     $reserved = $boat->reserved_days;
    //     $availables=$boat->available_days;

    //     foreach ($dates as $day) {
    //         if (($key =  array_search($day,$reserved)) !== FALSE) {
    //             unset($reserved[$key]);
    //             array_push($availables,$day);
    //         }
    //     }

    //     foreach ($reserved as $key=>$value) {
    //        $res [] =  $value;
    //     }

    //     $uniques = array_unique($availables);

    //     foreach ($uniques as $key => $value) {
    //        $new []= $value;
    //     }
    //     // unset($availables[0],$availables);

    //     if(!isset($new)){
    //        $boat->update([
    //         'available_days' => $new,
    //         'reserved_days' => $res,
    //         ]);
    //     }else{
    //            $boat->update([
    //                 'available_days' => $new,
    //                 'reserved_days' => $$res,
    //             ]);
    //     }

    // }
}
