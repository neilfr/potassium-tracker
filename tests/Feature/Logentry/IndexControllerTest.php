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
use phpDocumentor\Reflection\Types\Parent_;
use Tests\TestCase;

class IndexControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user, $foodgroup, $foodname, $measurename, $conversionfactor, $logentry;

    public function setUp(): void
    {
        parent::setUp();
        Carbon::setTestNow();
        $this->user = User::factory()->create();

        $this->foodgroup = Foodgroup::factory()->create();

        $this->foodname = Foodname::factory()->create([
            'FoodID' => 7,
            'FoodDescription' => 'My Food Description',
            'FoodGroupID' => $this->foodgroup->FoodGroupID,
        ]);

        $this->measurename = Measurename::factory()->create([
            'MeasureID' => 5,
        ]);

        $this->foodname->measurenames()->attach($this->measurename, [
            'id' => 9,
            'ConversionFactorValue' => 100,
        ]);

        $this->conversionfactor = Conversionfactor::query()
            ->where('MeasureID', $this->measurename->MeasureID)
            ->where('FoodID', $this->foodname->FoodID)
            ->first();

        $this->logentry = Logentry::factory()->create([
            'UserID' => $this->user->id,
            'ConversionFactorID' => $this->conversionfactor->id,
            'ConsumedAt' => now(),
        ]);

    }


    /** @test */
    public function it_can_return_a_list_of_log_entries()
    {
//        Carbon::setTestNow();
//        $user = User::factory()->create();
//
//        $foodgroup = Foodgroup::factory()->create();
//
//        $foodname = Foodname::factory()->create([
//            'FoodID' => 7,
//            'FoodDescription' => 'My Food Description',
//            'FoodGroupID' => $foodgroup->FoodGroupID,
//        ]);
//
//        $measurename = Measurename::factory()->create([
//            'MeasureID' => 5,
//        ]);
//
//        $foodname->measurenames()->attach($measurename, [
//            'id' => 9,
//            'ConversionFactorValue' => 100,
//        ]);
//
//        $conversionfactor = Conversionfactor::query()
//            ->where('MeasureID', $measurename->MeasureID)
//            ->where('FoodID', $foodname->FoodID)
//            ->first();
//
//        $logentry = Logentry::factory()->create([
//            'UserID' => $user->id,
//            'ConversionFactorID' => $conversionfactor->id,
//            'ConsumedAt' => now(),
//        ]);

        $response = $this->actingAs($this->user)->get(route('logentries.index'))
            ->assertSuccessful();

        $this->assertEquals($this->logentry->toArray()['ConversionFactorID'], $response->getOriginalContent()[0]['ConversionFactorID']);
        $this->assertEquals($this->logentry->toArray()['ConsumedAt'], $response->getOriginalContent()[0]['ConsumedAt']);
    }
}
