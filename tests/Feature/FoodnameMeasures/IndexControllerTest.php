<?php

namespace Tests\Feature\FoodnameMeasures;

use App\Models\Foodgroup;
use App\Models\Foodname;
use App\Models\Measurename;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexControllerTest extends TestCase
{
    use RefreshDatabase;

    //Should this be a unit test?
    /** @test */
    public function foodnames_have_measures()
    {
        $foodgroup = Foodgroup::factory()->create();
        $foodname = Foodname::factory()
            ->create([
                'FoodGroupID' => $foodgroup->FoodGroupID,
            ]);

        $measurename = Measurename::factory()->create();
        $foodname->measurenames()->attach($measurename, [
            'ConversionFactorValue' => 50
        ]);

        $this->assertEquals($measurename->MeasureDescription, $foodname->measurenames()->first()->MeasureDescription);
    }
}
