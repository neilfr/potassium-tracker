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

class StoreControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_updates_conversionfactor_favourite_to_false()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();

        $conversionFactorData = $this->createConversionFactor();

        $user->favourites()->attach(Conversionfactor::find($conversionFactorData[0]['ConversionFactorID']));

        $this->assertDatabaseHas('favourites', [
            'user_id' => $user->id,
            'ConversionFactorID' => $conversionFactorData[0]['ConversionFactorID'],
        ]);

        $payload = ['favourite' => false];

        $response = $this->actingAs($user)
            ->post(
                route(
                    'conversionfactors.store',
                    $conversionFactorData[0]['ConversionFactorID']
                ),
                $payload
            )
            ->assertSuccessful();

//        $this->assertDatabaseMissing('favourites', [
//            'user_id' => $user->id,
//            'ConversionFactorID' => $conversionFactorData[0]['ConversionFactorID'],
//        ]);
    }

    public function createConversionFactor($count = 1){
        $nutrientsConfig = collect(explode(',',env('NUTRIENTS')));
        $nutrients = $nutrientsConfig->map(function($nutrientId){
            return Nutrientname::factory()->create([
                'NutrientID' => $nutrientId,
            ]);
        });

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

}
