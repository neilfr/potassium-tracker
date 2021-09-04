<?php

namespace Tests\Feature\Conversionfactor;

use App\Models\Foodgroup;
use App\Models\Foodname;
use App\Models\Measurename;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Throwable;

class IndexControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $foodgroup = Foodgroup::factory()->create([
            'FoodGroupID' => 5
        ]);
        $foodname = Foodname::factory()->create([
            'FoodID' => 7,
            'FoodGroupID' => $foodgroup->FoodGroupID,
        ]);
        $measurenames = Measurename::factory()->count(2)->create();
        $conversionFactorValues = [1.01234, 2.12345];
        $measurenames->each( function ($measurename, $index) use($foodname, $conversionFactorValues) {
            $foodname->measurenames()->attach(
                $measurename,
                [
                    'ConversionFactorValue' => $conversionFactorValues[$index],
                ]
            );
        });

        $this->actingAs($user)->get(route('conversionfactors.index'))
            ->assertSuccessful()
            ->assertJson([
                "data" => [
                    [
                        'FoodID' => $foodname->FoodID,
                        'MeasureID' => $measurenames[0]->MeasureID,
                        'FoodGroupID' => $foodname->FoodGroupID,
                        'FoodCode' => $foodname->FoodCode,
                        'FoodDescription' => $foodname->FoodDescription,
                        'MeasureDescription' => $measurenames[0]->MeasureDescription,
                        'ConversionFactorValue' => $conversionFactorValues[0],
                    ],
                    [
                        'FoodID' => $foodname->FoodID,
                        'MeasureID' => $measurenames[1]->MeasureID,
                        'FoodGroupID' => $foodname->FoodGroupID,
                        'FoodCode' => $foodname->FoodCode,
                        'FoodDescription' => $foodname->FoodDescription,
                        'MeasureDescription' => $measurenames[1]->MeasureDescription,
                        'ConversionFactorValue' => $conversionFactorValues[1],
                    ],
                ]
            ]);
    }
}
