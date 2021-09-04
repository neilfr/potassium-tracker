<?php

namespace Tests\Feature\FoodnameMeasures;

use App\Models\Conversionfactor;
use App\Models\Foodgroup;
use App\Models\Foodname;
use App\Models\Measurename;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexControllerTest extends TestCase
{
    use RefreshDatabase;

    //Should this be a unit test?

    /** @test */
    public function foodnames_have_measures()
    {
        $foodgroup = Foodgroup::factory()->create();
        $foodname = Foodname::factory()
            ->create([
                'FoodGroupID' => $foodgroup->FoodGroupID,
            ]);

        $measurename = Measurename::factory()->create();
        $foodname->measurenames()->attach($measurename, [
            'ConversionFactorValue' => 1.2345
        ]);

        $this->assertEquals($measurename->MeasureDescription, $foodname->measurenames()->first()->MeasureDescription);
    }

    /** @test */
    public function foodnames_have_measures_with_conversion_factors()
    {
        $foodgroup = Foodgroup::factory()->create();
        $foodname = Foodname::factory()
            ->create([
                'FoodGroupID' => $foodgroup->FoodGroupID,
            ]);

        $measurenames = Measurename::factory()->count(2)->create();
        $conversionFactorValues = [1.2345,0.5];
        $measurenames->each( function ($measurename, $index) use($foodname, $conversionFactorValues) {
            $foodname->measurenames()->attach($measurename, [
               'ConversionFactorValue' => $conversionFactorValues[$index],
            ]);
        });

        $foodname->measurenames->each( function ($measurename, $index) use($foodname, $conversionFactorValues) {
            $this->assertEquals($conversionFactorValues[$index], $foodname->measurenames()->find($measurename->MeasureID)->pivot->ConversionFactorValue);
            $this->assertEquals($conversionFactorValues[$index], $measurename->foodnames()->find($foodname->FoodID)->pivot->ConversionFactorValue);
        });

    }

    /** @test */
    public function it_returns_foodname_rows_for_each_measure()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $foodgroup = Foodgroup::factory()->create();
        $foodname = Foodname::factory()
            ->create([
                'FoodGroupID' => $foodgroup->FoodGroupID,
            ]);

        $measurenames[0] = Measurename::factory()->create([
            'MeasureDescription' => 'measure0',
        ]);
        $measurenames[1] = Measurename::factory()->create([
            'MeasureDescription' => 'measure1',
        ]);

        $conversionFactorValues = [1.01234, 2.12345];
        collect($measurenames)->each( function ($measurename, $index) use($foodname, $conversionFactorValues) {
            $foodname->measurenames()->attach(
                $measurename,
                [
                    'ConversionFactorValue' => $conversionFactorValues[$index],
                ]
            );
        });

        $this->actingAs($user)->get(route('foodname-measures.index'))
            ->assertSuccessful()
            ->assertJson([
                "data" => [
                    [
                        'FoodID' => $foodname->FoodID,
                        'FoodDescription' => $foodname->FoodDescription,
                        'MeasureDescription' => $measurenames[0]->MeasureDescription,
                        'ConversionFactorValue' => $conversionFactorValues[0],
                    ],
                    [
                        'FoodID' => $foodname->FoodID,
                        'FoodDescription' => $foodname->FoodDescription,
                        'MeasureDescription' => $measurenames[1]->MeasureDescription,
                        'ConversionFactorValue' => $conversionFactorValues[1],
                    ],
                ]
            ]);
    }
}

