<?php

namespace Database\Factories;

use App\Models\Foodname;
use App\Models\Foodgroup;
use Illuminate\Database\Eloquent\Factories\Factory;

class FoodnameFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Foodname::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'FoodDescription' => $this->faker->sentence,
//            'FoodGroupID' => Foodgroup::factory()->create()->FoodGroupID,
            'FoodCode' => null,
        ];
    }

}
