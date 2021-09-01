<?php

namespace Database\Factories;

use App\Models\Measurename;
use Illuminate\Database\Eloquent\Factories\Factory;

class MeasurenameFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Measurename::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'MeasureDescription' => $this->faker->words(3, true),
        ];
    }
}
