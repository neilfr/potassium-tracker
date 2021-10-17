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
use Throwable;

class IndexControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_return_conversionfactor_table_rows_with_foodname_and_measurename_for_each_foodnames_measurename()
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
        $measurenames->each(function ($measurename, $index) use ($foodname, $conversionFactorValues) {
            $foodname->measurenames()->attach(
                $measurename,
                [
                    'ConversionFactorValue' => $conversionFactorValues[$index],
                ]
            );
        });

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

        $response = $this->actingAs($user)->get(route('conversionfactors.index'))
            ->assertSuccessful();
        $responseData = json_decode(json_encode($response->original->getData()['page']['props']), JSON_OBJECT_AS_ARRAY);
        $conversionfactorsResponseData = collect($responseData['conversionfactors']['data']);

        $this->assertCount(count($foodname->nutrientnames()->get()), $conversionfactorsResponseData);
        $conversionfactorsResponseData->each(function ($conversionfactor, $index) use ($foodname, $measurenames, $conversionFactorValues, $potassium, $potassiumValue) {
            $this->assertEquals($foodname->FoodID, $conversionfactor['FoodID']);
            $this->assertEquals($foodname->FoodGroupID, $conversionfactor['FoodGroupID']);
            $this->assertEquals($foodname->FoodCode, $conversionfactor['FoodCode']);
            $this->assertEquals($foodname->FoodDescription, $conversionfactor['FoodDescription']);
            $this->assertEquals($measurenames[$index]->MeasureDescription, $conversionfactor['MeasureDescription']);
            $this->assertEquals($conversionFactorValues[$index], $conversionfactor['ConversionFactorValue']);
            $this->assertArrayHasKey('nutrients', $conversionfactor);
        });
    }

    /** @test */
    public function it_returns_nutrients_with_a_conversionfactor()
    {
        $user = User::factory()->create();
        $foodgroup = Foodgroup::factory()->create([
            'FoodGroupID' => 5
        ]);
        $foodname = Foodname::factory()->create([
            'FoodID' => 7,
            'FoodGroupID' => $foodgroup->FoodGroupID,
        ]);
        $measurename = Measurename::factory()->create();
        $conversionFactorValue = 1.5;
        $foodname->measurenames()->attach(
            $measurename,
            [
                'ConversionFactorValue' => $conversionFactorValue,
            ]
        );

        $nutrientsConfig = collect(explode(',',env('NUTRIENTS')));

        $nutrients = $nutrientsConfig->map(function($nutrientId) use ($foodname) {
           $nutrient = Nutrientname::factory()->create([
               'NutrientID' => $nutrientId,
            ]);
           $foodname->nutrientnames()->attach($nutrient, [
               'NutrientValue' => rand(100, 200),
           ]);
           return $nutrient;
        });

        $response = $this->actingAs($user)->get(route('conversionfactors.index'))
            ->assertSuccessful();
        $responseData = json_decode(json_encode($response->original->getData()['page']['props']), JSON_OBJECT_AS_ARRAY);
        $conversionfactorResponseData = $responseData['conversionfactors']['data'];

        $this->assertCount(1, $conversionfactorResponseData);
        $this->assertArrayHasKey('nutrients', $conversionfactorResponseData[0]);

        $nutrientsResponse = collect($conversionfactorResponseData[0]['nutrients']);
        $this->assertCount(2, $nutrientsResponse);
        $nutrientResponseData = $conversionfactorResponseData[0]['nutrients'];

        $nutrients->each(function($nutrient) use($nutrientResponseData, $conversionFactorValue){
            $this->assertTrue($this->arrayHasArrayWithValue($nutrientResponseData,
                $nutrient->foodnames()->first()->pivot->NutrientValue * $conversionFactorValue
            ));
            $this->assertTrue($this->arrayHasArrayWithValue($nutrientResponseData, $nutrient->NutrientID));
            $this->assertTrue($this->arrayHasArrayWithValue($nutrientResponseData, $nutrient->NutrientSymbol));
            $this->assertTrue($this->arrayHasArrayWithValue($nutrientResponseData, $nutrient->NutrientUnit));
            $this->assertTrue($this->arrayHasArrayWithValue($nutrientResponseData, $nutrient->NutrientID));
        });
    }

    /** @test */
    public function it_returns_conversionfactor_with_favourite()
    {
        $user = User::factory()->create();

        $nutrients = $this->createNutrients();
        $conversionFactorData = $this->createConversionFactor($nutrients, $user->id, 2);

        $user->favourites()->attach(Conversionfactor::find($conversionFactorData[0]['ConversionFactorID']));

        $response = $this->actingAs($user)->get(route('conversionfactors.index'))
            ->assertSuccessful();
        $responseData = json_decode(json_encode($response->original->getData()['page']['props']), JSON_OBJECT_AS_ARRAY);

        $this->assertArrayHasKey('Favourite', $responseData['conversionfactors']['data'][0]);
        $this->assertEquals(true, $responseData['conversionfactors']['data'][0]['Favourite']);
        $this->assertArrayHasKey('Favourite', $responseData['conversionfactors']['data'][1]);
        $this->assertEquals(false, $responseData['conversionfactors']['data'][1]['Favourite']);

    }

    /** @test */
    public function it_returns_shared_and_owners_conversionfactors_only()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $anotherUser = User::factory()->create();

        $nutrients = $this->createNutrients();

        $sharedConversionFactor = $this->createConversionFactor($nutrients,null, 1);
        $usersConversionFactor = $this->createConversionFactor($nutrients, $user->id, 1);
        $anotherUsersConversionFactor = $this->createConversionFactor($nutrients, $anotherUser->id, 1);

        $response = $this->actingAs($user)->get(route('conversionfactors.index'))
            ->assertSuccessful();

        $responseData = json_decode(json_encode($response->original->getData()['page']['props']), JSON_OBJECT_AS_ARRAY);
        $this->assertCount(2, $responseData['conversionfactors']['data']);
    }

    public function arrayHasArrayWithValue($arrayOfArrays, $value)
    {
        return array_reduce($arrayOfArrays, function($acc, $array) use($value){
            return $acc + in_array($value, $array);
        },0) > 0;
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
