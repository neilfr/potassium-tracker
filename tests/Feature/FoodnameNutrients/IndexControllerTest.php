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
        $user = User::factory()->create();
        $foodgroup = Foodgroup::factory()->create();
        $foodname = Foodname::factory()
            ->create([
                'FoodGroupID' => $foodgroup->FoodGroupID,
            ]);

        $nutrientname = Nutrientname::factory()->create();
        $foodname->nutrientnames()->attach($nutrientname);

        $this->assertEquals($nutrientname->NutrientName, $foodname->nutrientnames()->first()->NutrientName);
    }

    /** @test */
    public function it_returns_foodnames_with_only_potassium_and_kilocalorie_nutrient_amounts()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $foodgroup = Foodgroup::factory()->create();
        $foodname = Foodname::factory()
            ->create([
                'FoodGroupID' => $foodgroup->FoodGroupID,
            ]);

        $nutrientnames[0] = Nutrientname::factory()->create([
            'NutrientName' => 'not potassium or kcal'
        ]);
        $nutrientnames[1] = Nutrientname::factory()->create([
            'NutrientID' => 306,
            'NutrientName' => 'POTASSIUM'
        ]);
        $nutrientnames[2] = Nutrientname::factory()->create([
            'NutrientID' => 208,
            'NutrientName' => 'ENERGY (KILOCALORIES)'
        ]);

        $foodname->nutrientnames()->sync(collect($nutrientnames)->pluck('NutrientID'));

        $response = $this->actingAs($user)->get(route('foodname-nutrients.index'))
            ->assertSuccessful()
            ->assertJson([
                "data" => [
                    [
                        'POTASSIUM' => 100,
                        'ENERGY (KILOCALORIES)' => 100,
                    ]
                ]
            ])
            ->assertJsonMissing([
                "data" => [
                    [
                        'not potassium or kcal' => 100,
                    ]
                ]
            ]);
    }

}
