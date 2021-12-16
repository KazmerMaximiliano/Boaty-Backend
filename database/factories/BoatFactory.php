<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Boat;
use App\Models\Type;
use App\Models\User;

class BoatFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Boat::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        return [
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->text,
            'price' => $this->faker->randomFloat(2, 0, 999999.99),
            'capacity' => $this->faker->numberBetween(1, 10),
            'coord' => $this->faker->regexify('[A-Za-z0-9]{40}'),
            'published_at' => $this->faker->dateTime(),
            'owner_id' => 2,
            'type_id' => Type::factory(),
        ];
    }
}
