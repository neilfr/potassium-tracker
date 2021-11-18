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

    protected $user, $conversionfactor, $conversionfactorData;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $nutrients = $this->createNutrients();
        $this->conversionfactorData = $this->createConversionFactor($nutrients, $this->user->id);
        $this->conversionfactor = Conversionfactor::find($this->conversionfactorData[0]['ConversionFactorID']);
    }

    /** @test */
    public function it_can_update_a_conversionfactor_foodname_description()
    {
        $payload = [
          'FoodDescription' => 'New Description'
        ];

        $this->assertDatabaseMissing('foodnames', [
            'FoodDescription' => $payload['FoodDescription'],
        ]);

        $this->actingAs($this->user)->patch(route('conversionfactors.update', $this->conversionfactor), $payload)
            ->assertRedirect();

        $this->assertDatabaseHas('foodnames', [
            'FoodDescription' => $payload['FoodDescription'],
        ]);

        $this->assertDatabaseMissing('foodnames', [
            'FoodDescription' => $this->conversionfactor->foodname->FoodDescription,
        ]);
    }

    /** @test */
    public function it_can_update_a_conversionfactor_measurename_description()
    {
        $payload = [
            'MeasureDescription' => 'New Description'
        ];

        $this->assertDatabaseMissing('measurenames', [
            'MeasureDescription' => $payload['MeasureDescription'],
        ]);

        $this->actingAs($this->user)->patch(route('conversionfactors.update', $this->conversionfactor), $payload)
            ->assertRedirect();

        $this->assertDatabaseHas('measurenames', [
            'MeasureDescription' => $payload['MeasureDescription'],
        ]);

        $this->assertDatabaseMissing('measurenames', [
            'MeasureDescription' => $this->conversionfactor->measurename->MeasureDescription,
        ]);
    }

    /** @test */
    public function it_can_update_conversionfactor_nutrient_amounts()
    {
        $payload = [
            'nutrients' => [
                [
                    'NutrientID' => $this->conversionfactorData[0]['NutrientData'][0]['NutrientID'],
                    'NutrientAmount' => '99',
                ],
                [
                    'NutrientID' => $this->conversionfactorData[0]['NutrientData'][1]['NutrientID'],
                    'NutrientAmount' => '11',
                ]
            ]
        ];

        $this->actingAs($this->user)->patch(route('conversionfactors.update', $this->conversionfactor), $payload)
            ->assertRedirect();

        $this->assertDatabaseHas('nutrientamounts', [
            'FoodID' => $this->conversionfactorData[0]['Foodname']->FoodID,
            'NutrientID' => $this->conversionfactorData[0]['NutrientData'][0]['NutrientID'],
            'NutrientValue' => round($payload['nutrients'][0]['NutrientAmount']/$this->conversionfactorData[0]['ConversionFactorValue']),
        ]);

        $this->assertDatabaseHas('nutrientamounts', [
            'FoodID' => $this->conversionfactorData[0]['Foodname']->FoodID,
            'NutrientID' => $this->conversionfactorData[0]['NutrientData'][1]['NutrientID'],
            'NutrientValue' => round($payload['nutrients'][1]['NutrientAmount']/$this->conversionfactorData[0]['ConversionFactorValue']),
        ]);
    }

    /** @test */
    public function it_must_have_a_food_description()
    {
        $payload = [
            'FoodDescription' => ''
        ];
        $this->actingAs($this->user)->patch(route('conversionfactors.update', $this->conversionfactor), $payload)
            ->assertSessionHasErrors('FoodDescription');
    }

    /** @test */
    public function it_must_have_a_measure_description()
    {
        $payload = [
            'MeasureDescription' => ''
        ];
        $this->actingAs($this->user)->patch(route('conversionfactors.update', $this->conversionfactor), $payload)
            ->assertSessionHasErrors('MeasureDescription');
    }

    /** @test */
    public function it_must_have_all_nutrient_amounts_not_less_than_0()
    {
        $payload = [
            'nutrients' => [
                [
                    'NutrientID' => $this->conversionfactorData[0]['NutrientData'][0]['NutrientID'],
                    'NutrientAmount' => '3',
                ],
                [
                    'NutrientID' => $this->conversionfactorData[0]['NutrientData'][1]['NutrientID'],
                    'NutrientAmount' => '-3',
                ]
            ]
        ];
        $this->actingAs($this->user)->patch(route('conversionfactors.update', $this->conversionfactor), $payload)
            ->assertSessionHasErrors('nutrients.*.NutrientAmount');
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
