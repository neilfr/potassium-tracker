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
    public function it_returns_foodnames_with_nutrient_amounts
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

}
