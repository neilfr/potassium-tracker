<?php

namespace Tests\Feature\Food;

use App\Models\Conversionfactor;
use App\Models\Favourite;
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

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /** @test */
    public function it_can_return_foods()
    {
        $food = $this->createFoods(1);
        $response = $this->actingAs($this->user)->get(route('foods.index'))
            ->assertSuccessful();

        $responseData = json_decode(json_encode($response->original->getData()['page']['props']), JSON_OBJECT_AS_ARRAY);
        $foodsResponseData = collect($responseData['foods']['data']);

        $this->assertCount(1,$foodsResponseData);
        $this->assertEquals($food[0]['UserID'], $foodsResponseData[0]['UserID']);
        $this->assertEquals($food[0]['FoodID'], $foodsResponseData[0]['FoodID']);
        $this->assertEquals($food[0]['FoodGroupID'], $foodsResponseData[0]['FoodGroupID']);
        $this->assertEquals($food[0]['MeasureID'], $foodsResponseData[0]['MeasureID']);
        $this->assertEquals($food[0]['FoodGroupName'], $foodsResponseData[0]['FoodGroupName']);
        $this->assertEquals($food[0]['FoodDescription'], $foodsResponseData[0]['FoodDescription']);
        $this->assertEquals($food[0]['MeasureDescription'], $foodsResponseData[0]['MeasureDescription']);
        $this->assertEquals($food[0]['ConversionFactorValue'], $foodsResponseData[0]['ConversionFactorValue']);
        $this->assertEquals($food[0]['KCalValue'], $foodsResponseData[0]['KCalValue']);
        $this->assertEquals($food[0]['KCalSymbol'], $foodsResponseData[0]['KCalSymbol']);
        $this->assertEquals($food[0]['KCalName'], $foodsResponseData[0]['KCalName']);
        $this->assertEquals($food[0]['KCalUnit'], $foodsResponseData[0]['KCalUnit']);
        $this->assertEquals($food[0]['PotassiumValue'], $foodsResponseData[0]['PotassiumValue']);
        $this->assertEquals($food[0]['PotassiumSymbol'], $foodsResponseData[0]['PotassiumSymbol']);
        $this->assertEquals($food[0]['PotassiumName'], $foodsResponseData[0]['PotassiumName']);
        $this->assertEquals($food[0]['PotassiumUnit'], $foodsResponseData[0]['PotassiumUnit']);
        $this->assertEquals($food[0]['NutrientDensity'], $foodsResponseData[0]['NutrientDensity']);
    }

    /** @test */
    public function it_can_return_food_with_favourite_data()
    {
        $foods = $this->createFoods(2);

        $fav1 = Favourite::factory()->create([
            'user_id' => $this->user->id,
            'ConversionFactorID' => $foods[1]->ConversionFactorID,
        ]);

        $response = $this->actingAs($this->user)->get(route('foods.index'))
            ->assertSuccessful();

        $responseData = json_decode(json_encode($response->original->getData()['page']['props']), JSON_OBJECT_AS_ARRAY);
        $foodsResponseData = collect($responseData['foods']['data']);

        $favourites = $foodsResponseData->filter(function($food){
            return $food['Favourite']===true;
        });

        $this->assertCount(1,$favourites);
        dd($favourites);

    }

    protected function createFoods($count)
    {
        $foods = [];
        for ($i = 0; $i < $count; $i++) {
            $foodgroup = Foodgroup::factory()->create();
            $measurename = Measurename::factory()->create();
            $foodname = Foodname::factory()->create([
                'FoodGroupID' => $foodgroup->FoodGroupID,
            ]);
            $conversionfactorvalue = rand(1,5);
            $foodname->measurenames()->attach(
                $measurename,
                [
                    'ConversionFactorValue' => $conversionfactorvalue
                ]
            );

            $conversionfactor = Conversionfactor::where('MeasureID', '=', $measurename->MeasureID)
                ->where('FoodID', '=', $foodname->FoodID)
                ->first();
            $foods[$i] = (
                Food::factory()->create([
                    'ConversionFactorID' => $conversionfactor->id,
                    'UserID' => $this->user->id,
                    'FoodGroupID' => $foodgroup->FoodGroupID,
                    'FoodID' => $foodname->FoodID,
                    'MeasureID' => $measurename->MeasureID,
                    'FoodGroupName' => $foodgroup->FoodGroupName,
                    'FoodDescription' => $foodname->FoodDescription,
                    'MeasureDescription' => $measurename->MeasureDescription,
                    'ConversionFactorValue' => $conversionfactorvalue,
                    'KCalValue' => $kcal = rand(1,100),
                    'KCalSymbol' => 'KCal',
                    'KCalName' => 'Energy (Kilocalories)',
                    'KCalUnit' => 'kcal',
                    'PotassiumValue' => $k = rand(1,100),
                    'PotassiumSymbol' => 'K',
                    'PotassiumName' => 'Potassium',
                    'PotassiumUnit' => 'mg',
                    'NutrientDensity' => $kcal / $k,
                ])
            );
        }
        return collect($foods);
    }

}
