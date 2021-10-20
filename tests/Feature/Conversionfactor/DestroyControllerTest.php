<?php

namespace Tests\Feature\Conversionfactor;

use App\Models\Conversionfactor;
use App\Models\Foodgroup;
use App\Models\Foodname;
use App\Models\Logentry;
use App\Models\Measurename;
use App\Models\Nutrientname;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DestroyControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_delete_a_conversionfactor()
    {
        $user = User::factory()->create();

        $nutrients = $this->createNutrients();
        $conversionfactorData = $this->createConversionFactor($nutrients, $user->id);

        $conversionfactor = Conversionfactor::find($conversionfactorData[0]['ConversionFactorID']);

        $this->assertDatabaseHas('conversionfactors', [
            'id' => $conversionfactor->id,
        ]);

        $response = $this->actingAs($user)->delete(route('conversionfactors.destroy', $conversionfactor))
            ->assertSuccessful();

        $this->assertDatabaseMissing('conversionfactors', [
            'id' => $conversionfactor->id,
        ]);

    }

    /** @test */
    public function it_cannot_delete_a_conversionfactor_the_user_does_not_own()
    {
        $user = User::factory()->create();
        $anotherUser = User::factory()->create();

        $nutrients = $this->createNutrients();
        $conversionfactorData = $this->createConversionFactor($nutrients, $anotherUser->id);

        $conversionfactor = Conversionfactor::find($conversionfactorData[0]['ConversionFactorID']);

        $this->assertDatabaseHas('conversionfactors', [
            'id' => $conversionfactor->id,
        ]);

        $response = $this->actingAs($user)->delete(route('conversionfactors.destroy', $conversionfactor))
            ->assertSuccessful();

        $this->assertDatabaseHas('conversionfactors', [
            'id' => $conversionfactor->id,
        ]);

    }

    /** @test */
    public function it_deletes_related_foodname_measurename_and_nutrientamounts()
    {
        $user = User::factory()->create();

        $nutrients = $this->createNutrients();
        $conversionfactorData = $this->createConversionFactor($nutrients, $user->id);

        $conversionfactor = Conversionfactor::find($conversionfactorData[0]['ConversionFactorID']);

        $this->assertDatabaseHas('measurenames', [
            'MeasureID' => $conversionfactor->measurename->MeasureID
        ]);
        $this->assertDatabaseHas('foodnames', [
           'FoodID' => $conversionfactor->foodname->FoodID
        ]);
        $conversionfactor->foodname->nutrientnames->each(function ($nutrient) use($conversionfactor) {
            $this->assertDatabaseHas('nutrientamounts', [
                'FoodID' =>  $conversionfactor->foodname->FoodID,
                'NutrientID' => $nutrient->NutrientID,
            ]);
        });

        $response = $this->actingAs($user)->delete(route('conversionfactors.destroy', $conversionfactor))
            ->assertSuccessful();

        $this->assertDatabaseMissing('measurenames', [
            'MeasureID' => $conversionfactor->measurename->MeasureID
        ]);
        $this->assertDatabaseMissing('foodnames', [
            'FoodID' => $conversionfactor->foodname->FoodID
        ]);
        $conversionfactor->foodname->nutrientnames->each(function ($nutrient) use($conversionfactor) {
            $this->assertDatabaseMissing('nutrientamounts', [
                'FoodID' =>  $conversionfactor->foodname->FoodID,
                'NutrientID' => $nutrient->NutrientID,
            ]);
        });
    }

    /** @test */
    public function it_only_deletes_conversionfactors_if_they_do_not_have_corresponding_logentries()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();

        $nutrients = $this->createNutrients();
        $conversionfactorData = $this->createConversionFactor($nutrients, $user->id);

        $conversionfactor = Conversionfactor::find($conversionfactorData[0]['ConversionFactorID']);

        $logentry = Logentry::factory()->create([
            'UserID' => $user->id,
            'ConversionFactorID' => $conversionfactor->id,
            'ConsumedAt' => now()->toDateString(),
        ]);

        $response = $this->actingAs($user)->delete(route('conversionfactors.destroy', $conversionfactor))
            ->assertSuccessful();

        $this->assertDatabaseHas('conversionfactors', [
            'id' => $conversionfactor->id,
        ]);

    }

    public function createConversionFactor($nutrients, $owner_id, $count = 1)
    {
        $data = [];
        for($i=0;$i<$count;$i++){
            $foodgroup = Foodgroup::factory()->create();
            $foodname = Foodname::factory()->create([
                'FoodGroupID' => $foodgroup->FoodGroupID,
            ]);
            $measurename = Measurename::factory()->create();

            $conversionFactorValue = rand(1,5);
            $foodname->measurenames()->attach(
                $measurename,
                [
                    'ConversionFactorValue' => $conversionFactorValue,
                    'user_id' => $owner_id,
                ]
            );

            $nutrientData = $nutrients->map(function($nutrient) use ($foodname) {
                $nutrientValue = rand(100,200);
                $foodname->nutrientnames()->attach($nutrient, [
                    'NutrientValue' => $nutrientValue,
                ]);
                return array_merge($nutrient->toArray(), ['nutrient_value' => $nutrientValue]);
            });
            $data[$i] = [
                'ConversionFactorID' => $foodname->measurenames()->first()->pivot->id,
                'ConversionFactorValue' => $conversionFactorValue,
                'Foodgroup' => $foodgroup,
                'Foodname' => $foodname,
                'Measurename' => $measurename,
                'NutrientData' => $nutrientData,
            ];
        }

        return $data;
    }

    protected function createNutrients(): \Illuminate\Support\Collection
    {
        $nutrientsConfig = collect(explode(',', env('NUTRIENTS')));
        $nutrients = $nutrientsConfig->map(function ($nutrientId) {
            return Nutrientname::factory()->create([
                'NutrientID' => $nutrientId,
            ]);
        });
        return $nutrients;
    }

}
