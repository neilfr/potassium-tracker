<?php

namespace Tests\Feature\Conversionfactor;

use App\Models\Conversionfactor;
use App\Models\Favourite;
use App\Models\Foodgroup;
use App\Models\Foodname;
use App\Models\Measurename;
use App\Models\Nutrientname;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\TestHelpers;
use Throwable;

class IndexControllerTest extends TestCase
{
    use RefreshDatabase, TestHelpers;

    /** @test */
    public function it_returns_favourite_conversionfactors_by_default_with_foodname_and_measurename_for_each_foodnames_measurename()
    {
        $user = User::factory()->create();
        $nutrients = $this->createNutrients();
        $conversionfactorData = $this->createConversionFactor($nutrients, $user->id, 2);

        collect($conversionfactorData)->each(function($conversionfactordataitem) use($user) {
            Favourite::factory()->create([
                'ConversionFactorID' => $conversionfactordataitem['ConversionFactorID'],
                'user_id' => $user->id,
            ]);
        });

        $response = $this->actingAs($user)->get(route('conversionfactors.index'))
            ->assertSuccessful();
        $responseData = json_decode(json_encode($response->original->getData()['page']['props']), JSON_OBJECT_AS_ARRAY);

        $this->assertCount(count($conversionfactorData), $responseData['conversionfactors']['data']);

        collect($responseData['conversionfactors']['data'])->each(function ($conversionfactorresponse, $index) use ($conversionfactorData) {
            $this->assertEquals($conversionfactorresponse['FoodID'], $conversionfactorData[$index]['Foodname']->FoodID);
            $this->assertEquals($conversionfactorresponse['FoodGroupID'], $conversionfactorData[$index]['Foodgroup']->FoodGroupID);
            $this->assertEquals($conversionfactorresponse['FoodCode'], $conversionfactorData[$index]['Foodname']->FoodCode);
            $this->assertEquals($conversionfactorresponse['FoodDescription'], $conversionfactorData[$index]['Foodname']->FoodDescription);
            $this->assertEquals($conversionfactorresponse['MeasureDescription'], $conversionfactorData[$index]['Measurename']->MeasureDescription);
            $this->assertEquals($conversionfactorresponse['ConversionFactorValue'], $conversionfactorData[$index]['ConversionFactorValue']);
            $this->assertArrayHasKey('nutrients', $conversionfactorresponse);
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


        Favourite::factory()->create([
            'ConversionFactorID' => Conversionfactor::first()->id,
            'user_id' => $user->id,
        ]);

        $nutrients = $this->createNutrients();
        $nutrients->each(function($nutrient, $index) use ($foodname) {
            $foodname->nutrientnames()->attach($nutrient, [
               'NutrientValue' => ($index + 1) * 100,
           ]);
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
    public function it_returns_nutrients_with_nutrient_amount_of_na_if_nutrient_does_not_exist_for_conversionfactor()
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
        $measurename = Measurename::factory()->create();
        $conversionFactorValue = 1.5;
        $foodname->measurenames()->attach(
            $measurename,
            [
                'ConversionFactorValue' => $conversionFactorValue,
            ]
        );

        Favourite::factory()->create([
            'ConversionFactorID' => Conversionfactor::first()->id,
            'user_id' => $user->id,
        ]);

        $nutrients = $this->createNutrients();

        $foodname->nutrientnames()->attach($nutrients[1], [
            'NutrientValue' => 100,
        ]);

        $response = $this->actingAs($user)->get(route('conversionfactors.index'))
            ->assertSuccessful();
        $responseData = json_decode(json_encode($response->original->getData()['page']['props']), JSON_OBJECT_AS_ARRAY);
        $conversionfactorResponseData = $responseData['conversionfactors']['data'];

        $nutrientsResponse = collect($conversionfactorResponseData[0]['nutrients']);
        $this->assertCount(2, $nutrientsResponse);

        $nutrientResponseData = $conversionfactorResponseData[0]['nutrients'];

        $this->assertTrue($this->arrayHasArrayWithValue($nutrientResponseData,
            $nutrients[1]->foodnames()->first()->pivot->NutrientValue * $conversionFactorValue
        ));
        $this->assertTrue($this->arrayHasArrayWithValue($nutrientResponseData,
            'NA'
        ));
    }

    /** @test */
    public function it_returns_conversionfactor_with_favourite()
    {
        $user = User::factory()->create();

        $nutrients = $this->createNutrients();
        $conversionFactorData = $this->createConversionFactor($nutrients, $user->id, 2);
        $user->favourites()->attach(Conversionfactor::find($conversionFactorData[0]['ConversionFactorID']));

        $response = $this->actingAs($user)->get(route('conversionfactors.index', ['favouritefilter' => 'no']))
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
        $user = User::factory()->create();
        $anotherUser = User::factory()->create();

        $nutrients = $this->createNutrients();

        $sharedConversionFactor = $this->createConversionFactor($nutrients,null, 1);
        $usersConversionFactor = $this->createConversionFactor($nutrients, $user->id, 1);
        $anotherUsersConversionFactor = $this->createConversionFactor($nutrients, $anotherUser->id, 1);

        $response = $this->actingAs($user)->get(route('conversionfactors.index',  ['favouritefilter' => 'no']))
            ->assertSuccessful();

        $responseData = json_decode(json_encode($response->original->getData()['page']['props']), JSON_OBJECT_AS_ARRAY);
        $this->assertCount(2, $responseData['conversionfactors']['data']);
        $this->assertEquals('user_id', array_search(null, $responseData['conversionfactors']['data'][0]));
        $this->assertEquals('user_id', array_search($user->id, $responseData['conversionfactors']['data'][1]));
    }

    /** @test */
    public function it_returns_nutrientdensity_with_a_conversionfactor()
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
        $conversionFactorValue = 1;
        $foodname->measurenames()->attach(
            $measurename,
            [
                'ConversionFactorValue' => $conversionFactorValue,
            ]
        );

        Favourite::factory()->create([
            'ConversionFactorID' => Conversionfactor::first()->id,
            'user_id' => $user->id,
        ]);

        $nutrients = $this->createNutrients();

        $nutrientsDensityItems = collect(explode(',', env('NUTRIENT_DENSITY')));

        $numeratorNutrientValue = 100;
        $numeratorNutrient = collect($nutrients->filter(function($nutrient) use($nutrientsDensityItems) {
            return $nutrient['NutrientID'] ==  $nutrientsDensityItems[0];
        }))->first();
        $foodname->nutrientnames()->attach($numeratorNutrient, ['NutrientValue' => $numeratorNutrientValue]);

        $denominatorNutrientValue = 200;
        $denominatorNutrient = collect($nutrients->filter(function($nutrient) use($nutrientsDensityItems) {
            return $nutrient['NutrientID'] ==  $nutrientsDensityItems[1];
        }))->first();
        $foodname->nutrientnames()->attach($denominatorNutrient, ['NutrientValue' => $denominatorNutrientValue]);

        $expectedNutrientDensityValue = $numeratorNutrientValue/$denominatorNutrientValue;
        $expectedNutrientDensityUnit = $numeratorNutrient['NutrientUnit'] . ' ' . $numeratorNutrient['NutrientSymbol'] .
            ' / ' . $denominatorNutrient['NutrientUnit'] . ' ' . $denominatorNutrient['NutrientSymbol'];

        $response = $this->actingAs($user)->get(route('conversionfactors.index'))
            ->assertSuccessful();
        $responseData = json_decode(json_encode($response->original->getData()['page']['props']), JSON_OBJECT_AS_ARRAY);
        $conversionfactorResponseData = $responseData['conversionfactors']['data'];

        $this->assertArrayHasKey('NutrientDensityUnit',$conversionfactorResponseData[0]);
        $this->assertEquals($expectedNutrientDensityUnit, $conversionfactorResponseData[0]['NutrientDensityUnit']);
        $this->assertArrayHasKey('NutrientDensityValue',$conversionfactorResponseData[0]);
        $this->assertEquals($expectedNutrientDensityValue, $conversionfactorResponseData[0]['NutrientDensityValue']);
    }

    /** @test */
    public function it_returns_NA_nutrientdensityvalue_and_if_denominator_nutrient_is_0_with_a_conversionfactor()
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
        $conversionFactorValue = 1;
        $foodname->measurenames()->attach(
            $measurename,
            [
                'ConversionFactorValue' => $conversionFactorValue,
            ]
        );

        Favourite::factory()->create([
            'ConversionFactorID' => Conversionfactor::first()->id,
            'user_id' => $user->id,
        ]);

        $nutrientDensityItems = collect(explode(',', env('NUTRIENT_DENSITY')));

        $numeratorNutrient = Nutrientname::factory()->create([
            'NutrientID' => $nutrientDensityItems[0],
        ]);
        $numeratorNutrientValue = 100;
        $foodname->nutrientnames()->attach($numeratorNutrient, ['NutrientValue' => $numeratorNutrientValue]);

        $denominatorNutrient = Nutrientname::factory()->create([
            'NutrientID' => $nutrientDensityItems[1],
        ]);
        $denominatorNutrientValue = 0;
        $foodname->nutrientnames()->attach($denominatorNutrient, ['NutrientValue' => $denominatorNutrientValue]);

        $expectedNutrientDensityValue = 'NA';
        $expectedNutrientDensityUnit = $numeratorNutrient['NutrientUnit'] . ' ' . $numeratorNutrient['NutrientSymbol'] .
            ' / ' . $denominatorNutrient['NutrientUnit'] . ' ' . $denominatorNutrient['NutrientSymbol'];

        $response = $this->actingAs($user)->get(route('conversionfactors.index'))
            ->assertSuccessful();
        $responseData = json_decode(json_encode($response->original->getData()['page']['props']), JSON_OBJECT_AS_ARRAY);
        $conversionfactorResponseData = $responseData['conversionfactors']['data'];

        $this->assertArrayHasKey('NutrientDensityUnit',$conversionfactorResponseData[0]);
        $this->assertEquals($expectedNutrientDensityUnit, $conversionfactorResponseData[0]['NutrientDensityUnit']);
        $this->assertArrayHasKey('NutrientDensityValue',$conversionfactorResponseData[0]);
        $this->assertEquals($expectedNutrientDensityValue, $conversionfactorResponseData[0]['NutrientDensityValue']);
    }

    public function arrayHasArrayWithValue($arrayOfArrays, $value)
    {
        return array_reduce($arrayOfArrays, function($acc, $array) use($value){
            return $acc + in_array($value, $array);
        },0) > 0;
    }
}
