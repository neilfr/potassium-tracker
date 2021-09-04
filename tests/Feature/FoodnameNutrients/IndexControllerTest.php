<?php

namespace Tests\Feature\FoodnameNutrients;

use App\Models\Foodgroup;
use App\Models\Foodname;
use App\Models\Nutrientname;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class IndexControllerTest extends TestCase
{
    use RefreshDatabase;

    //Should this be a unit test?
    /** @test */
    public function foodnames_have_nutrients()
    {
        $foodgroup = Foodgroup::factory()->create();
        $foodname = Foodname::factory()
            ->create([
                'FoodGroupID' => $foodgroup->FoodGroupID,
            ]);

        $nutrientname = Nutrientname::factory()->create();
        $foodname->nutrientnames()->attach($nutrientname, [
            'NutrientValue' => 50
        ]);

        $this->assertEquals($nutrientname->NutrientName, $foodname->nutrientnames()->first()->NutrientName);
    }

    //Should this be a unit test?
    /** @test */
    public function foodnames_have_nutrients_with_nutrient_values()
    {
        $foodgroup = Foodgroup::factory()->create();
        $foodname = Foodname::factory()
            ->create([
                'FoodGroupID' => $foodgroup->FoodGroupID,
            ]);

        $nutrientnames = Nutrientname::factory()->count(2)->create();
        $nutrientValues = [50.5, 150.5];
        $nutrientnames->each( function ($nutrientname, $index) use($foodname, $nutrientValues) {
           $foodname->nutrientnames()->attach($nutrientname, [
              'NutrientValue' => $nutrientValues[$index],
           ]);
        });

        $foodname->nutrientnames->each( function ($nutrientname, $index) use($foodname, $nutrientValues) {
           $this->assertEquals($nutrientValues[$index], $foodname->nutrientnames()->find($nutrientname->NutrientID)->pivot->NutrientValue);
           $this->assertEquals($nutrientValues[$index], $nutrientname->foodnames()->find($foodname->FoodID)->pivot->NutrientValue);
        });
    }

    /** @test */
    public function it_returns_foodnames_with_only_potassium_and_kilocalorie_nutrient_amounts()
    {
        $user = User::factory()->create();
        $foodgroup = Foodgroup::factory()->create();
        $foodname = Foodname::factory()
            ->create([
                'FoodGroupID' => $foodgroup->FoodGroupID,
            ]);

        $someNutrient = Nutrientname::factory()->create([
            'NutrientName' => 'not potassium or kcal'
        ]);
        $potassium = Nutrientname::factory()->create([
            'NutrientID' => 306,
            'NutrientName' => 'POTASSIUM'
        ]);
        $kcal = Nutrientname::factory()->create([
            'NutrientID' => 208,
            'NutrientName' => 'ENERGY (KILOCALORIES)'
        ]);

        $foodname->nutrientnames()->attach($someNutrient, [
            'NutrientValue' => 50
        ]);
        $foodname->nutrientnames()->attach($potassium, [
            'NutrientValue' => 100.6
        ]);
        $foodname->nutrientnames()->attach($kcal, [
            'NutrientValue' => 150.568
        ]);

        $this->actingAs($user)->get(route('foodname-nutrients.index'))
            ->assertSuccessful()
            ->assertJson([
                "data" => [
                    [
                        'POTASSIUM' => 100.6,
                        'ENERGY (KILOCALORIES)' => 150.568,
                    ]
                ]
            ])
            ->assertJsonMissing([
                "data" => [
                    [
                        'not potassium or kcal' => 50,
                    ]
                ]
            ]);
    }

}
