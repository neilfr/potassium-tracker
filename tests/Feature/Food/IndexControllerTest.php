<?php

namespace Tests\Feature\Food;

use App\Models\Conversionfactor;
use App\Models\Food;
use App\Models\Foodgroup;
use App\Models\Foodname;
use App\Models\Measurename;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class IndexControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_return_foods()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();

        $foodgroup = Foodgroup::factory()->create();
        $measurename = Measurename::factory()->create();
        $foodname = Foodname::factory()->create([
            'FoodGroupID' => $foodgroup->FoodGroupID,
        ]);
        $conversionfactorvalue=5;
        $foodname->measurenames()->attach(
            $measurename,
            [
                'ConversionFactorValue' => $conversionfactorvalue
            ]
        );

        $conversionfactor = Conversionfactor::where('MeasureID', '=', $measurename->MeasureID)
            ->where('FoodID', '=', $foodname->FoodID)
            ->first();

        $foods = Food::factory()->count(5)->create([
            'ConversionFactorID' => $conversionfactor->id,
            'UserID' => $user->id,
            'FoodGroupID' => $foodgroup->FoodGroupID,
            'FoodID' => $foodname->FoodID,
            'MeasureID' => $measurename->MeasureID,
            'FoodGroupName' => $foodgroup->FoodGroupName,
            'FoodDescription'=> $foodname->FoodDescription,
            'MeasureDescription' => $measurename->MeasureDescription,
            'ConversionFactorValue' => $conversionfactorvalue,
            'KCalValue' => 5,
            'KCalSymbol' => 'KCal',
            'KCalName' => 'Energy (Kilocalories)',
            'KCalUnit' => 'kcal',
            'PotassiumValue' => 100,
            'PotassiumSymbol' => 'K',
            'PotassiumName' => 'Potassium',
            'PotassiumUnit' => 'mg',
            'NutrientDensity' => '0.05',
        ]);

        $response = $this->actingAs($user)->get(route('foods.index'))
            ->assertSuccessful();

        $responseData = json_decode(json_encode($response->original->getData()['page']['props']), JSON_OBJECT_AS_ARRAY);
        $foodsResponseData = collect($responseData['foods']['data']);

        $this->assertCount(5,$foodsResponseData);
        $foodsResponseData->each(function($food, $index) use($foodsResponseData) {
           $this->assertEquals($food['UserID'], $foodsResponseData[$index]['UserID']);
           $this->assertEquals($food['FoodID'], $foodsResponseData[$index]['FoodID']);
           $this->assertEquals($food['FoodGroupID'], $foodsResponseData[$index]['FoodGroupID']);
           $this->assertEquals($food['MeasureID'], $foodsResponseData[$index]['MeasureID']);
           $this->assertEquals($food['FoodGroupName'], $foodsResponseData[$index]['FoodGroupName']);
           $this->assertEquals($food['FoodDescription'], $foodsResponseData[$index]['FoodDescription']);
           $this->assertEquals($food['MeasureDescription'], $foodsResponseData[$index]['MeasureDescription']);
           $this->assertEquals($food['ConversionFactorValue'], $foodsResponseData[$index]['ConversionFactorValue']);
           $this->assertEquals($food['KCalValue'], $foodsResponseData[$index]['KCalValue']);
           $this->assertEquals($food['KCalSymbol'], $foodsResponseData[$index]['KCalSymbol']);
           $this->assertEquals($food['KCalName'], $foodsResponseData[$index]['KCalName']);
           $this->assertEquals($food['KCalUnit'], $foodsResponseData[$index]['KCalUnit']);
           $this->assertEquals($food['PotassiumValue'], $foodsResponseData[$index]['PotassiumValue']);
           $this->assertEquals($food['PotassiumSymbol'], $foodsResponseData[$index]['PotassiumSymbol']);
           $this->assertEquals($food['PotassiumName'], $foodsResponseData[$index]['PotassiumName']);
           $this->assertEquals($food['PotassiumUnit'], $foodsResponseData[$index]['PotassiumUnit']);
           $this->assertEquals($food['NutrientDensity'], $foodsResponseData[$index]['NutrientDensity']);
        });
    }

}
