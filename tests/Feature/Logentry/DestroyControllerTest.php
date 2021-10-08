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
use Tests\TestCase;

class DestroyControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_destroys_logentry()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $foodgroup=Foodgroup::factory()->create();
        $foodname=Foodname::factory()->create([
            'FoodGroupID' => $foodgroup->FoodGroupID,
        ]);
        $measurename = Measurename::factory()->create();

        $conversionfactor = Conversionfactor::factory()->create([
            'id'=>99,
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
            'ConversionFactorID' => $conversionfactor->id,
        ]);

        $this->actingAs($user)->delete(route('logentries.destroy',$logentry))
            ->assertRedirect();

        $this->assertDatabaseMissing('logentries', [
           'ConversionFactorID' => $conversionfactor->id,
        ]);


    }
}
