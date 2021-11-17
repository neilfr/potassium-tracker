<?php

namespace Tests\Feature\Conversionfactor;

use App\Models\Foodgroup;
use App\Models\Foodname;
use App\Models\Measurename;
use App\Models\Nutrientname;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StoreControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user, $foodgroup, $potassium, $kcal, $payload;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();

        $this->foodgroup = Foodgroup::factory()->create();

        $this->potassium = Nutrientname::factory()->create([
            'NutrientID' => 306,
            'NutrientName' => 'POTASSIUM',
            'NutrientSymbol' => 'K'
        ]);

        $this->kcal = Nutrientname::factory()->create([
            'NutrientID' => 208,
            'NutrientName' => 'ENERGY (KILOCALORIES)',
            'NutrientSymbol' => 'KCAL'
        ]);

        $this->payload = [
            'foodGroupId' => $this->foodgroup->FoodGroupID,
            'foodDescription' => 'my new food',
            'measureDescription' => '125 ml',
            'kcal' => 82,
            'k' => 85,
        ];

    }

    /** @test */
    public function it_can_store_a_new_conversionfactor()
    {
        $this->actingAs($this->user)->post(route('conversionfactors.store'), $this->payload)
            ->assertRedirect();

        $foodname = Foodname::first();
        $measurename = Measurename::first();

        $this->assertDatabaseHas('foodgroups', [
            'FoodGroupID' => $this->payload['foodGroupId'],
        ]);

        $this->assertDatabaseHas('foodnames', [
            'FoodDescription' => $this->payload['foodDescription'],
            'FoodGroupID' => $this->foodgroup->FoodGroupID,
        ]);

        $this->assertDatabaseHas('measurenames', [
            'MeasureDescription' => $this->payload['measureDescription']
        ]);

        $this->assertDatabaseHas('conversionfactors', [
            'FoodID' => $foodname->FoodID,
            'MeasureID' => $measurename->MeasureID,
            'ConversionFactorValue' => 1,
            'user_id' => $this->user->id,
        ]);

        $this->assertDatabaseHas('nutrientamounts', [
            'FoodID' => $foodname->FoodID,
            'NutrientID' => 306,
            'NutrientValue' => $this->payload['k'],
        ]);

        $this->assertDatabaseHas('nutrientamounts', [
            'FoodID' => $foodname->FoodID,
            'NutrientID' => 208,
            'NutrientValue' => $this->payload['kcal'],
        ]);
    }

    /** @test */
    public function it_must_have_a_food_description()
    {
        $this->payload['foodDescription'] = '';
        $this->actingAs($this->user)->post(route('conversionfactors.store'), $this->payload)
            ->assertSessionHasErrors('foodDescription');
    }

    /** @test */
    public function it_must_have_a_measure_description()
    {
        $this->payload['measureDescription'] = '';
        $this->actingAs($this->user)->post(route('conversionfactors.store'), $this->payload)
            ->assertSessionHasErrors('measureDescription');
    }

    /** @test */
    public function it_must_have_a_non_negative_kcal()
    {
        $this->payload['kcal'] = -5;
        $this->actingAs($this->user)->post(route('conversionfactors.store'), $this->payload)
            ->assertSessionHasErrors('kcal');
    }

    /** @test */
    public function it_must_have_a_non_negative_k()
    {
        $this->payload['k'] = -5;
        $this->actingAs($this->user)->post(route('conversionfactors.store'), $this->payload)
            ->assertSessionHasErrors('k');
    }
}
