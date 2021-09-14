<?php

namespace Tests\Feature\Logentry;

use App\Models\Conversionfactor;
use App\Models\Foodgroup;
use App\Models\Foodname;
use App\Models\Logentry;
use App\Models\Measurename;
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

        $logentry = Logentry::factory()->create([
           'ConversionFactorID' => $conversionfactor->id,
           'ConsumedAt' => now(),
        ]);

        $response = $this->actingAs($user)->get(route('logentries.index'))
            ->assertSuccessful();

        $this->assertEquals($logentry->toArray()['ConversionFactorID'], $response->getOriginalContent()[0]['ConversionFactorID']);
        $this->assertEquals($logentry->toArray()['ConsumedAt'], $response->getOriginalContent()[0]['ConsumedAt']);
    }
}
