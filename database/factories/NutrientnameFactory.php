<?php

namespace Database\Factories;

use App\Models\Nutrientname;
use Illuminate\Database\Eloquent\Factories\Factory;

class NutrientnameFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Nutrientname::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'NutrientName' => $this->faker->word,
            'NutrientSymbol' => $this->faker->word,
            'NutrientUnit' => $this->faker->word,
        ];
    }
}
