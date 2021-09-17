<?php

namespace Tests\Feature\Logentry;

use App\Models\Conversionfactor;
use App\Models\Foodgroup;
use App\Models\Foodname;
use App\Models\Logentry;
use App\Models\Measurename;
use App\Models\Nutrientname;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class IndexControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_return_a_list_of_log_entries()
    {
        Carbon::setTestNow();
        $user = User::factory()->create();

        $foodgroup = Foodgroup::factory()->create();

        $foodname = Foodname::factory()->create([
            'FoodID' => 7,
            'FoodDescription' => 'My Food Description',
            'FoodGroupID' => $foodgroup->FoodGroupID,
        ]);

        $measurename = Measurename::factory()->create([
            'MeasureID' => 5,
        ]);

        $foodname->measurenames()->attach($measurename, [
            'id' => 9,
            'ConversionFactorValue' => 100,
        ]);

        $conversionfactor = Conversionfactor::query()
            ->where('MeasureID', $measurename->MeasureID)
            ->where('FoodID', $foodname->FoodID)
            ->first();

        $logentries = Logentry::factory()->count(2)->create([
            'UserID' => $user->id,
            'ConversionFactorID' => $conversionfactor->id,
            'ConsumedAt' => now(),
        ]);

        $anotherUser = User::factory()->create();
        $anotherUsersLogEntry = Logentry::factory()->create([
            'UserID' => $anotherUser->id,
            'ConversionFactorID' => $conversionfactor->id,
            'ConsumedAt' => now(),
        ]);

        $response = $this->actingAs($user)->get(route('logentries.index'))
            ->assertSuccessful();

        $data = collect($response->original->getData()['page']['props']['logentries']);

        $this->assertCount(2,$data);
        $data->each(function($logentry, $index) use ($logentries){
            $this->assertEquals($logentries[$index]->toArray()['UserID'], $logentry['UserID']);
            $this->assertEquals($logentries[$index]->toArray()['ConversionFactorID'], $logentry['ConversionFactorID']);
            $this->assertEquals($logentries[$index]->toArray()['ConsumedAt'], $logentry['ConsumedAt']);
        });
    }

    /** @test */
    public function it_returns_logentries_with_foodname_and_measurename()
    {
        Carbon::setTestNow();
        $user = User::factory()->create();

        $foodgroup = Foodgroup::factory()->create();

        $foodname = Foodname::factory()->create([
            'FoodID' => 7,
            'FoodDescription' => 'My Food Description',
            'FoodGroupID' => $foodgroup->FoodGroupID,
        ]);

        $measurename = Measurename::factory()->create([
            'MeasureID' => 5,
        ]);

        $foodname->measurenames()->attach($measurename, [
            'id' => 9,
            'ConversionFactorValue' => 100,
        ]);

        $conversionfactor = Conversionfactor::query()
            ->where('MeasureID', $measurename->MeasureID)
            ->where('FoodID', $foodname->FoodID)
            ->first();

        $logentries = Logentry::factory()->count(2)->create([
            'UserID' => $user->id,
            'ConversionFactorID' => $conversionfactor->id,
            'ConsumedAt' => now(),
        ]);

        $response = $this->actingAs($user)->get(route('logentries.index'))
            ->assertSuccessful();

        $responseData = json_decode(json_encode($response->original->getData()['page']['props']), JSON_OBJECT_AS_ARRAY);
        $logentriesResponseData = collect($responseData['logentries']['data']);
        $this->assertCount(count($logentries),$logentriesResponseData);

        $logentriesResponseData->each(function($logentry, $index) use ($logentries, $foodname, $measurename){
            $this->assertEquals($logentries[$index]->toArray()['UserID'], $logentry['UserID']);
            $this->assertEquals($logentries[$index]->toArray()['ConversionFactorID'], $logentry['ConversionFactorID']);
            $this->assertEquals($logentries[$index]->toArray()['ConsumedAt'], $logentry['ConsumedAt']);
            $this->assertEquals($foodname->FoodDescription, $logentry['FoodDescription']);
            $this->assertEquals($measurename->MeasureDescription, $logentry['MeasureDescription']);
        });

    }

    /** @test */
    public function it_returns_logentries_with_foodname_measurename_and_nutrient_values()
    {
        Carbon::setTestNow();
        $user = User::factory()->create();

        $foodgroup = Foodgroup::factory()->create();

        $foodname = Foodname::factory()->create([
            'FoodID' => 7,
            'FoodDescription' => 'My Food Description',
            'FoodGroupID' => $foodgroup->FoodGroupID,
        ]);

        $potassium = Nutrientname::factory()->create([
            'NutrientID' => 306,
            'NutrientName' => 'POTASSIUM'
        ]);
        $kcal = Nutrientname::factory()->create([
            'NutrientID' => 208,
            'NutrientName' => 'ENERGY (KILOCALORIES)'
        ]);
        $foodname->nutrientnames()->attach($potassium, [
            'NutrientValue' => 100.6
        ]);
        $foodname->nutrientnames()->attach($kcal, [
            'NutrientValue' => 150.568
        ]);

        $measurename = Measurename::factory()->create([
            'MeasureID' => 5,
        ]);

        $foodname->measurenames()->attach($measurename, [
            'id' => 9,
            'ConversionFactorValue' => 100,
        ]);

        $conversionfactor = Conversionfactor::query()
            ->where('MeasureID', $measurename->MeasureID)
            ->where('FoodID', $foodname->FoodID)
            ->first();

        $logentries = Logentry::factory()->count(2)->create([
            'UserID' => $user->id,
            'ConversionFactorID' => $conversionfactor->id,
            'ConsumedAt' => now(),
        ]);

        $response = $this->actingAs($user)->get(route('logentries.index'))
            ->assertSuccessful();

        $responseData = json_decode(json_encode($response->original->getData()['page']['props']), JSON_OBJECT_AS_ARRAY);
        $logentriesResponseData = collect($responseData['logentries']['data']);
        $this->assertCount(count($logentries), $logentriesResponseData);

        $logentriesResponseData->each(function ($logentry, $index) use ($logentries, $foodname, $measurename) {
            $this->assertEquals($logentries[$index]->toArray()['UserID'], $logentry['UserID']);
            $this->assertEquals($logentries[$index]->toArray()['ConversionFactorID'], $logentry['ConversionFactorID']);
            $this->assertEquals($logentries[$index]->toArray()['ConsumedAt'], $logentry['ConsumedAt']);
            $this->assertEquals($foodname->FoodDescription, $logentry['FoodDescription']);
            $this->assertEquals($measurename->MeasureDescription, $logentry['MeasureDescription']);
            $this->assertEquals(100.6, $logentry['POTASSIUM']);
            $this->assertEquals(150.568, $logentry['ENERGY (KILOCALORIES)']);
        });
    }
}
