<?php

namespace Tests\Feature\Conversionfactor;

use App\Models\Conversionfactor;
use App\Models\Foodgroup;
use App\Models\Foodname;
use App\Models\Measurename;
use App\Models\Nutrientname;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_update_a_conversionfactor_foodname_description()
    {
        $user = User::factory()->create();

        $nutrients = $this->createNutrients();
        $conversionfactorData = $this->createConversionFactor($nutrients, $user->id);

        $conversionfactor = Conversionfactor::find($conversionfactorData[0]['ConversionFactorID']);

        $this->assertDatabaseHas('foodnames', [
            'FoodDescription' => $conversionfactor->foodname->FoodDescription,
        ]);

        $payload = [
          'FoodDescription' => 'New Description'
        ];

        $this->assertDatabaseMissing('foodnames', [
            'FoodDescription' => $payload['FoodDescription'],
        ]);

        $response = $this->actingAs($user)->patch(route('conversionfactors.update', $conversionfactor), $payload)
            ->assertSuccessful();

        $this->assertDatabaseHas('foodnames', [
            'FoodDescription' => $payload['FoodDescription'],
        ]);

        $this->assertDatabaseMissing('foodnames', [
            'FoodDescription' => $conversionfactor->foodname->FoodDescription,
        ]);
    }

    /** @test */
    public function it_can_update_a_conversionfactor_measurename_description()
    {
        $user = User::factory()->create();

        $nutrients = $this->createNutrients();
        $conversionfactorData = $this->createConversionFactor($nutrients, $user->id);

        $conversionfactor = Conversionfactor::find($conversionfactorData[0]['ConversionFactorID']);

        $this->assertDatabaseHas('measurenames', [
            'MeasureDescription' => $conversionfactor->measurename->MeasureDescription,
        ]);

        $payload = [
            'MeasureDescription' => 'New Description'
        ];

        $this->assertDatabaseMissing('measurenames', [
            'MeasureDescription' => $payload['MeasureDescription'],
        ]);

        $response = $this->actingAs($user)->patch(route('conversionfactors.update', $conversionfactor), $payload)
            ->assertSuccessful();

        $this->assertDatabaseHas('measurenames', [
            'MeasureDescription' => $payload['MeasureDescription'],
        ]);

        $this->assertDatabaseMissing('measurenames', [
            'MeasureDescription' => $conversionfactor->measurename->MeasureDescription,
        ]);
    }


    /** @test */
    public function it_can_update_conversionfactor_nutrient_amounts()
    {
        $user = User::factory()->create();

        $nutrients = $this->createNutrients();
        $conversionfactorData = $this->createConversionFactor($nutrients, $user->id);
        $conversionfactor = Conversionfactor::find($conversionfactorData[0]['ConversionFactorID']);

        $this->assertDatabaseHas('nutrientamounts', [
            'FoodID' => $conversionfactorData[0]['Foodname']->FoodID,
            'NutrientID' => $conversionfactorData[0]['NutrientData'][0]['NutrientID'],
            'NutrientValue' => $conversionfactorData[0]['NutrientData'][0]['nutrient_value'],
        ]);

        $payload = [
            'nutrients' => [
                [
                    'NutrientID' => $conversionfactorData[0]['NutrientData'][0]['NutrientID'],
                    'NutrientAmount' => '99',
                ],
                [
                    'NutrientID' => $conversionfactorData[0]['NutrientData'][1]['NutrientID'],
                    'NutrientAmount' => '11',
                ]
            ]
        ];

        $response = $this->actingAs($user)->patch(route('conversionfactors.update', $conversionfactor), $payload)
            ->assertRedirect();

        $this->assertDatabaseHas('nutrientamounts', [
            'FoodID' => $conversionfactorData[0]['Foodname']->FoodID,
            'NutrientID' => $conversionfactorData[0]['NutrientData'][0]['NutrientID'],
            'NutrientValue' => round($payload['nutrients'][0]['NutrientAmount']/$conversionfactorData[0]['ConversionFactorValue']),
        ]);

        $this->assertDatabaseHas('nutrientamounts', [
            'FoodID' => $conversionfactorData[0]['Foodname']->FoodID,
            'NutrientID' => $conversionfactorData[0]['NutrientData'][1]['NutrientID'],
            'NutrientValue' => round($payload['nutrients'][1]['NutrientAmount']/$conversionfactorData[0]['ConversionFactorValue']),
        ]);
    }

    public function createConversionFactor($nutrients, $owner_id, $count = 1)
    {
        $data = [];
        for($i=0;$i<$count;$i++){
            $foodgroup = Foodgroup::factory()->create();
            $foodname = Foodname::factory()->create([
                'FoodGroupID' => $foodgroup->FoodGroupID,
            ]);
            $measurename = Measurename::factory()->create();

            $conversionFactorValue = rand(1,5);
            $foodname->measurenames()->attach(
                $measurename,
                [
                    'ConversionFactorValue' => $conversionFactorValue,
                    'user_id' => $owner_id,
                ]
            );

            $nutrientData = $nutrients->map(function($nutrient) use ($foodname) {
                $nutrientValue = rand(100,200);
                $foodname->nutrientnames()->attach($nutrient, [
                    'NutrientValue' => $nutrientValue,
                ]);
                return array_merge($nutrient->toArray(), ['nutrient_value' => $nutrientValue]);
            });
            $data[$i] = [
                'ConversionFactorID' => $foodname->measurenames()->first()->pivot->id,
                'ConversionFactorValue' => $conversionFactorValue,
                'Foodgroup' => $foodgroup,
                'Foodname' => $foodname,
                'Measurename' => $measurename,
                'NutrientData' => $nutrientData,
            ];
        }

        return $data;
    }

    protected function createNutrients(): \Illuminate\Support\Collection
    {
        $nutrientsConfig = collect(explode(',', env('NUTRIENTS')));
        $nutrients = $nutrientsConfig->map(function ($nutrientId) {
            return Nutrientname::factory()->create([
                'NutrientID' => $nutrientId,
            ]);
        });
        return $nutrients;
    }

}
