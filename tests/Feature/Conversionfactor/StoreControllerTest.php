<?php

namespace Tests\Feature\Conversionfactor;

use App\Models\Foodgroup;
use App\Models\Foodname;
use App\Models\Measurename;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StoreControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_store_a_new_conversionfactor()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();

        $foodgroup = Foodgroup::factory()->create();

        $payload = [
            'foodGroupId' => $foodgroup->FoodGroupID,
            'foodDescription' => 'my new food',
            'measureDescription' => '125 ml',
            'kcal' => 82,
            'k' => 85,
        ];

        $this->actingAs($user)->post(route('conversionfactors.store'), $payload);

        $foodname = Foodname::first();
        $measurename = Measurename::first();

        $this->assertDatabaseHas('foodgroups', [
            'FoodGroupName' => $payload['foodGroupName'],
        ]);

        $this->assertDatabaseHas('foodnames', [
            'FoodDescription' => $payload['foodDescription'],
            'FoodGroupID' => $foodgroup->FoodGroupID,
        ]);

        $this->assertDatabaseHas('measurenames', [
            'MeasureDescription' => $payload['measureDescription']
        ]);

        $this->assertDatabaseHas('conversionfactors', [
            'FoodID' => $foodname->id,
            'MeasureID' => $measurename->id,
            'ConversionFactorValue' => 1
        ]);

        $this->assertDatabaseHas('nutrientamounts', [
            'FoodID' => $foodname->id,
            'NutrientID' => 306,
            'NutrientValue' => $payload['k'],
        ]);

        $this->assertDatabaseHas('nutrientamounts', [
            'FoodID' => $foodname->id,
            'NutrientID' => 208,
            'NutrientValue' => $payload['kcal'],
        ]);
    }
}
