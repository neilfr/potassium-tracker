<?php

namespace Tests\Feature\Logentry;

use App\Models\Conversionfactor;
use App\Models\Foodgroup;
use App\Models\Foodname;
use App\Models\Logentry;
use App\Models\Measurename;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Carbon\Carbon;
use Tests\TestCase;

class UpdateControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_update_a_logentry()
    {
        $this->withoutExceptionHandling();
        Carbon::setTestNow();
        $user = User::factory()->create();
        $foodgroup=Foodgroup::factory()->create();
        $foodname=Foodname::factory()->create([
            'FoodGroupID' => $foodgroup->FoodGroupID,
        ]);
        $measurename = Measurename::factory()->create();

        $conversionfactor = Conversionfactor::factory()->create([
            'id'=>1,
            'FoodID' => $foodname->FoodID,
            'MeasureID' => $measurename->MeasureID,
            'ConversionFactorValue' => 5
        ]);

        $logentry = Logentry::factory()->create([
            'UserID' => $user->id,
            'ConsumedAt' => now()->toDateString(),
            'ConversionFactorID' => $conversionfactor->id,
        ]);

        $this->assertDatabaseHas('logentries', [
            'UserID' => $user->id,
            'ConsumedAt' => now()->toDateString(),
            'ConversionFactorID' => $conversionfactor->id,
        ]);

        $payload = [
            'ConsumedAt' => now()->addDays(5)->toDateString(),
        ];

        $this->actingAs($user)->patch(route('logentries.update', $logentry), $payload)
            ->assertRedirect();

        $this->assertDatabaseHas('logentries', [
            'UserID' => $user->id,
            'ConsumedAt' => now()->addDays(5)->toDateString(),
            'ConversionFactorID' => $conversionfactor->id,
        ]);    }
}
