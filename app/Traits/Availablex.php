<?php

namespace App\Traits;


trait Available
{

    public function subDays($dates, $availavility){

        $availables = $availavility->available_days;

        foreach ($dates as $day) {

            if (($key =  array_search($day,$availables)) !== FALSE) {
                unset($availables[$key]);
            }
        }

        foreach ($availables as $key=>$value) {
           $new [] =  $value;
        }
        if(!isset($new)){
           $availavility->update([
            'available_days' => [],
            ]);
        }else{
               $availavility->update([
                    'available_days' => $new,
                ]);
        }

    }

    public function addDays($dates,$availavility){
        $res = array_merge($availavility->available_days,$dates);
        $uniques = array_unique($res);

        foreach ($uniques as $key => $value) {
           $new []= $value;
        }
        $availavility->update([
            'available_days' => $new,
        ]);
    }

    public function cancelDays($dates,$availavility){

        $reserved = $availavility->reserved_days;

        foreach ($dates as $day) {

            if (($key =  array_search($day,$reserved)) !== FALSE) {
                unset($reserved[$key]);
            }
        }

        foreach ($reserved as $key=>$value) {
           $new [] =  $value;
        }
        if(!isset($new)){
           $availavility->update([
            'reserved_days' => [],
            ]);
        }else{
               $availavility->update([
                    'reserved_days' => $new,
                ]);
        }

    }

    public function updateDays($dates,$availavility){
        $res = array_merge($availavility->available_days,$dates);
        $uniques = array_unique($res);

        foreach ($uniques as $key => $value) {
           $new []= $value;
        }
        $availavility->update([
            'available_days' => $new,
        ]);
    }
}
