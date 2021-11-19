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

    protected $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();

    }

    /** @test */
    public function it_returns_todays_logentries_with_foodname_measurename_and_nutrient_values_symbol_and_units()
    {
        Carbon::setTestNow();
        $this->user = User::factory()->create();

        $foodgroup = Foodgroup::factory()->create();

        $foodname = Foodname::factory()->create([
            'FoodID' => 7,
            'FoodDescription' => 'My Food Description',
            'FoodGroupID' => $foodgroup->FoodGroupID,
        ]);

        $potassium = Nutrientname::factory()->create([
            'NutrientID' => 306,
            'NutrientName' => 'POTASSIUM',
            'NutrientSymbol' => 'K',
            'NutrientUnit' => 'mg',
        ]);
        $potassiumNutrientValue = 100.6;
        $foodname->nutrientnames()->attach($potassium, [
            'NutrientValue' => $potassiumNutrientValue
        ]);

        $kcal = Nutrientname::factory()->create([
            'NutrientID' => 208,
            'NutrientName' => 'ENERGY (KILOCALORIES)',
            'NutrientSymbol' => 'KCAL',
            'NutrientUnit' => 'kCal',
        ]);
        $kcalNutrientValue = 50.568;
        $foodname->nutrientnames()->attach($kcal, [
            'NutrientValue' => $kcalNutrientValue
        ]);

        $measurename = Measurename::factory()->create([
            'MeasureID' => 5,
        ]);
        $conversionFactorValue = 100;
        $foodname->measurenames()->attach($measurename, [
            'id' => 9,
            'ConversionFactorValue' => $conversionFactorValue,
        ]);
        $conversionfactor = Conversionfactor::query()
            ->where('MeasureID', $measurename->MeasureID)
            ->where('FoodID', $foodname->FoodID)
            ->first();

        $logentries = Logentry::factory()->count(2)->create([
            'UserID' => $this->user->id,
            'ConversionFactorID' => $conversionfactor->id,
            'ConsumedAt' => now()->toDateString(),
        ]);

        $response = $this->actingAs($this->user)->get(route('logentries.index'))
            ->assertSuccessful();

        $responseData = json_decode(json_encode($response->original->getData()['page']['props']), JSON_OBJECT_AS_ARRAY);
        $logentriesResponseData = collect($responseData['logentries']['data']);
        $this->assertCount(count($logentries), $logentriesResponseData);

        $logentriesResponseData->each(function ($logentry, $index)
            use ($logentries, $foodname, $measurename, $potassiumNutrientValue, $conversionFactorValue, $kcalNutrientValue, $potassium, $kcal)
            {
                $this->assertEquals($logentries[$index]->toArray()['UserID'], $logentry['UserID']);
                $this->assertEquals($logentries[$index]->toArray()['ConversionFactorID'], $logentry['ConversionFactorID']);
                $this->assertEquals(Carbon::parse($logentries[$index]->toArray()['ConsumedAt'])->toDateString(), Carbon::parse($logentry['ConsumedAt'])->toDateString());
                $this->assertEquals($foodname->FoodDescription, $logentry['FoodDescription']);
                $this->assertEquals($measurename->MeasureDescription, $logentry['MeasureDescription']);
                $this->assertCount(2, $logentry['nutrients']);
                collect($logentry['nutrients'])->each(function($nutrient, $key){
                    $this->assertArrayHasKey('NutrientID', $nutrient);
                    $this->assertArrayHasKey('NutrientName', $nutrient);
                    $this->assertArrayHasKey('NutrientSymbol', $nutrient);
                    $this->assertArrayHasKey('NutrientUnit', $nutrient);
                    $this->assertArrayHasKey('NutrientValue', $nutrient['pivot']);
                });
            });
    }

    /** @test */
    public function it_returns_logentries_with_foodname_measurename_and_nutrient_values_symbol_and_units_for_a_date_range()
    {
        Carbon::setTestNow();

        $this->user = User::factory()->create();

        $foodgroup = Foodgroup::factory()->create();

        $foodname = Foodname::factory()->create([
            'FoodID' => 7,
            'FoodDescription' => 'My Food Description',
            'FoodGroupID' => $foodgroup->FoodGroupID,
        ]);

        $potassium = Nutrientname::factory()->create([
            'NutrientID' => 306,
            'NutrientName' => 'POTASSIUM',
            'NutrientSymbol' => 'K',
            'NutrientUnit' => 'mg',
        ]);
        $potassiumNutrientValue = 100.6;
        $foodname->nutrientnames()->attach($potassium, [
            'NutrientValue' => $potassiumNutrientValue
        ]);

        $kcal = Nutrientname::factory()->create([
            'NutrientID' => 208,
            'NutrientName' => 'ENERGY (KILOCALORIES)',
            'NutrientSymbol' => 'KCAL',
            'NutrientUnit' => 'kCal',
        ]);
        $kcalNutrientValue = 50.568;
        $foodname->nutrientnames()->attach($kcal, [
            'NutrientValue' => $kcalNutrientValue
        ]);

        $measurename = Measurename::factory()->create([
            'MeasureID' => 5,
        ]);
        $conversionFactorValue = 100;
        $foodname->measurenames()->attach($measurename, [
            'id' => 9,
            'ConversionFactorValue' => $conversionFactorValue,
        ]);
        $conversionfactor = Conversionfactor::query()
            ->where('MeasureID', $measurename->MeasureID)
            ->where('FoodID', $foodname->FoodID)
            ->first();

        // old logentry within range
        $logentries[0] = Logentry::factory()->create([
            'UserID' => $this->user->id,
            'ConversionFactorID' => $conversionfactor->id,
            'ConsumedAt' => now()->copy()->addDays(2)->toDateString(),
        ]);
        // future logentry within range
        $logentries[1] = Logentry::factory()->create([
            'UserID' => $this->user->id,
            'ConversionFactorID' => $conversionfactor->id,
            'ConsumedAt' => now()->copy()->subDays(2)->toDateString(),
        ]);
        // future logentry out of range
        $logentries[2] = Logentry::factory()->create([
            'UserID' => $this->user->id,
            'ConversionFactorID' => $conversionfactor->id,
            'ConsumedAt' => now()->copy()->addDays(7)->toDateString(),
        ]);
        // old logentry out of range
        $logentries[3] = Logentry::factory()->create([
            'UserID' => $this->user->id,
            'ConversionFactorID' => $conversionfactor->id,
            'ConsumedAt' => now()->copy()->subDays(7)->toDateString(),
        ]);

        $response = $this->actingAs($this->user)->get(route('logentries.index', [
            'from' => now()->copy()->subDays(5)->toDateString(),
            'to' => now()->copy()->addDays(5)->toDateString(),
        ]))
            ->assertSuccessful();

        $responseData = json_decode(json_encode($response->original->getData()['page']['props']), JSON_OBJECT_AS_ARRAY);
        $logentriesResponseData = collect($responseData['logentries']['data']);

        $this->assertCount(2, $logentriesResponseData);

        $logentriesResponseData->each(function ($logentry, $index)
        use ($logentries, $foodname, $measurename, $potassiumNutrientValue, $conversionFactorValue, $kcalNutrientValue, $potassium, $kcal)
        {
            $this->assertEquals($logentries[$index]->toArray()['UserID'], $logentry['UserID']);
            $this->assertEquals($logentries[$index]->toArray()['ConversionFactorID'], $logentry['ConversionFactorID']);
            $this->assertEquals(Carbon::parse($logentries[$index]->toArray()['ConsumedAt'])->toDateString(), Carbon::parse($logentry['ConsumedAt'])->toDateString());
            $this->assertEquals($foodname->FoodDescription, $logentry['FoodDescription']);
            $this->assertEquals($measurename->MeasureDescription, $logentry['MeasureDescription']);
            $this->assertCount(2, $logentry['nutrients']);
            collect($logentry['nutrients'])->each(function($nutrient, $key){
                $this->assertArrayHasKey('NutrientID', $nutrient);
                $this->assertArrayHasKey('NutrientName', $nutrient);
                $this->assertArrayHasKey('NutrientSymbol', $nutrient);
                $this->assertArrayHasKey('NutrientUnit', $nutrient);
                $this->assertArrayHasKey('NutrientValue', $nutrient['pivot']);
            });
        });
    }

    /** @test */
    public function it_returns_logentries_with_nutrient_totals()
    {
        $this->user = User::factory()->create();

        $foodgroup = Foodgroup::factory()->create();

        $foodname = Foodname::factory()->create([
            'FoodID' => 7,
            'FoodDescription' => 'My Food Description',
            'FoodGroupID' => $foodgroup->FoodGroupID,
        ]);

        $nutrientIds = collect(explode(',', env('NUTRIENTS')));

        $nutrientIds->each(function($nutrientId) use($foodname){
            $nutrient = Nutrientname::factory()->create([
                'NutrientID' => $nutrientId,
            ]);
            $foodname->nutrientnames()->attach($nutrient, [
                'NutrientValue' => rand(10,100),
            ]);
            return $nutrient;
        });

        $expectedTotals = $foodname->nutrientnames->map(function($nutrient, $index) use($foodname){
            return [
                'NutrientID' => $foodname->nutrientnames[$index]->NutrientID,
                'ExpectedTotal' => $foodname->nutrientnames[$index]->pivot->NutrientValue * 2
            ];
        });

        $measurename = Measurename::factory()->create([
            'MeasureID' => 5,
        ]);
        $conversionFactorValue = 100;
        $foodname->measurenames()->attach($measurename, [
            'id' => 9,
            'ConversionFactorValue' => $conversionFactorValue,
        ]);
        $conversionfactor = Conversionfactor::query()
            ->where('MeasureID', $measurename->MeasureID)
            ->where('FoodID', $foodname->FoodID)
            ->first();

        $logentries = Logentry::factory()->count(2)->create([
            'UserID' => $this->user->id,
            'ConversionFactorID' => $conversionfactor->id,
            'ConsumedAt' => now(),
        ]);

        $response = $this->actingAs($this->user)->get(route('logentries.index'))
            ->assertSuccessful();

        $responseData = json_decode(json_encode($response->original->getData()['page']['props']), JSON_OBJECT_AS_ARRAY);
        $nutrientTotalsResponseData = collect($responseData['nutrienttotals']['data']);

        $this->assertCount(count($logentries), $nutrientTotalsResponseData);

        $nutrientTotalsResponseData->each(function ($nutrientTotal, $index) use($expectedTotals, $conversionFactorValue) {
            $this->assertArrayHasKey('NutrientID', $nutrientTotal);
            $this->assertArrayHasKey('NutrientName', $nutrientTotal);
            $this->assertArrayHasKey('NutrientSymbol', $nutrientTotal);
            $this->assertArrayHasKey('NutrientUnit', $nutrientTotal);
            $this->assertArrayHasKey('total', $nutrientTotal);
            $this->assertEquals(
                $expectedTotals->where('NutrientID', $nutrientTotal['NutrientID'])->first()['ExpectedTotal']
                * $conversionFactorValue,
                $nutrientTotal['total']
            );
        });
    }
}
