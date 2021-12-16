<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Boat;
use App\Models\Gallery;

class GalleryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Gallery::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $boats = Boat::all();

        return [
            'path' => '/img/boats/boaty'. rand(1, 12) .'.png',
            'boat_id' => rand(1,count($boats)),
        ];
    }
}
