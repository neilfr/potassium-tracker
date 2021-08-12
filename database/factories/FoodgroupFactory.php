<?php

namespace Database\Factories;

use App\Models\Foodgroup;
use Illuminate\Database\Eloquent\Factories\Factory;

class FoodgroupFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Foodgroup::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->words(3,true),
        ];
    }
}
