<?php

namespace Tests\Feature\Logentry;

use App\Models\Conversionfactor;
use App\Models\Foodgroup;
use App\Models\Foodname;
use App\Models\Measurename;
use App\Models\User;
use Database\Factories\ConversionfactorFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StoreControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_stores_a_new_logentry()
    {
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

        $this->actingAs($user)->post(route('logentries.store'),$conversionfactor->toArray())
            ->assertSuccessful();

        $this->assertDatabaseHas('logentries', [
            'ConversionFactorID' => $conversionfactor->id,
        ]);
    }
}
