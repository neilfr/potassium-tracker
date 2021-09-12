<?php

namespace Tests\Feature\Logentry;

use App\Models\Conversionfactor;
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

        $measurename = Measurename::factory()->create();

        $foodname = Foodname::factory()->create([
            'FoodDescription' => 'My Food Description',
        ]);

        $conversionfactor = Conversionfactor::factory()->create([
            'ConversionFactorValue' => 100,
            'FoodID' => $foodname->FoodID,
            'MeasureID' => $measurename->MeasureID,
        ]);

        $logentry = Logentry::factory()->create([
           'ConversionFactorID' => $conversionfactor->id,
           'ConsumedAt' => now(),
        ]);

        $response = $this->actingAs($user)->get(route('/logentries'))
            ->assertSuccessful();

        $this->assertEquals($logentry->toArray()['ConversionFactorID'], $response->getOriginalContent()[0]['ConversionFactorID']);
        $this->assertEquals($logentry->toArray()['ConsumedAt'], $response->getOriginalContent()[0]['ConsumedAt']);
    }
}
