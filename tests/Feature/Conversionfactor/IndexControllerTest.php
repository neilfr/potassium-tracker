<?php

namespace Tests\Feature\Conversionfactor;

use App\Models\Foodgroup;
use App\Models\Foodname;
use App\Models\Measurename;
use App\Models\Nutrientname;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Throwable;

class IndexControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_return_conversionfactor_table_rows_with_foodname_and_measure_data_with_scaled_potassium_and_kcal()
    {
        $user = User::factory()->create();
        $foodgroup = Foodgroup::factory()->create([
            'FoodGroupID' => 5
        ]);
        $foodname = Foodname::factory()->create([
            'FoodID' => 7,
            'FoodGroupID' => $foodgroup->FoodGroupID,
        ]);
        $measurenames = Measurename::factory()->count(2)->create();
        $conversionFactorValues = [1.5, 2.5];
        $measurenames->each( function ($measurename, $index) use($foodname, $conversionFactorValues) {
            $foodname->measurenames()->attach(
                $measurename,
                [
                    'ConversionFactorValue' => $conversionFactorValues[$index],
                ]
            );
        });

        $nutrientIds = explode(',', env('NUTRIENTS'));

        $potassium = Nutrientname::factory()->create([
            'NutrientID' => 306,
            'NutrientName' => 'POTASSIUM',
            'NutrientSymbol' => 'K',
            'NutrientUnit' => 'mg',
        ]);
        $kcal = Nutrientname::factory()->create([
            'NutrientID' => 208,
            'NutrientName' => 'ENERGY (KILOCALORIES)',
            'NutrientSymbol' => 'KCAL',
            'NutrientUnit' => 'kCal',
        ]);

        $potassiumValue = 100;
        $foodname->nutrientnames()->attach($potassium, [
            'NutrientValue' => $potassiumValue
        ]);
        $kcalValue = 150;
        $foodname->nutrientnames()->attach($kcal, [
            'NutrientValue' => $kcalValue
        ]);

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
                        'POTASSIUM' => $potassiumValue * $conversionFactorValues[0],
                        'ENERGY (KILOCALORIES)' => $kcalValue * $conversionFactorValues[0],
                    ],
                    [
                        'FoodID' => $foodname->FoodID,
                        'MeasureID' => $measurenames[1]->MeasureID,
                        'FoodGroupID' => $foodname->FoodGroupID,
                        'FoodCode' => $foodname->FoodCode,
                        'FoodDescription' => $foodname->FoodDescription,
                        'MeasureDescription' => $measurenames[1]->MeasureDescription,
                        'ConversionFactorValue' => $conversionFactorValues[1],
                        'POTASSIUM' => $potassiumValue * $conversionFactorValues[1],
                        'ENERGY (KILOCALORIES)' => $kcalValue * $conversionFactorValues[1],
                    ],
                ]
            ]);
    }
}
