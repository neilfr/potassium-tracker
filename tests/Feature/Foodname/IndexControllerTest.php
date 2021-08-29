<?php

namespace Tests\Feature\Foodname;

use App\Models\Foodname;
use App\Models\Foodgroup;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class IndexControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_return_foodnames_as_an_authenticated_user()
    {
        $user = User::factory()->create();
        $foodgroup = Foodgroup::factory()->create();
        $foods = Foodname::factory()->count(10)->create([
            'FoodGroupID' => $foodgroup->FoodGroupID,
        ]);

        $response = $this->actingAs($user)->get(route('foodnames.index'))
            ->assertSuccessful();

        $this->assertEquals($foods->toArray(), $response->getOriginalContent()->toArray());
    }

    /** @test */
    public function it_cannot_return_foodnames_as_an_unauthenticated_user()
    {
        $foodgroup = Foodgroup::factory()->create();

        Foodname::factory()->count(10)->create([
            'FoodGroupID' => $foodgroup->FoodGroupID,
        ]);

        $this->get(route('foodnames.index'))
            ->assertRedirect();
    }

    /** @test */
    public function it_returns_foodnames_with_descriptions()
    {
        $user = User::factory()->create();
        $foodgroup = Foodgroup::factory()->create();
        $foods = Foodname::factory(5)->create([
            'FoodGroupID' => $foodgroup->FoodGroupID,
        ]);

        $response = $this->actingAs($user)->get(route('foodnames.index'))
            ->assertSuccessful();

        $response->getOriginalContent()->each(function($food, $key) use($foods) {
           $this->assertEquals($foods->toArray()[$key]['FoodDescription'], $food->toArray()['FoodDescription']);
        });
    }

    /** @test */
    //FIX THIS
    public function it_returns_foodnames_with_foodgroup()
    {
        $user = User::factory()->create();
        $foodgroup = Foodgroup::factory()->create();

        $foodnames = Foodname::factory(5)->create([
            'FoodGroupID' => $foodgroup->FoodGroupID,
        ]);
        dd($foodnames[0]->foodgroup->FoodGroupName);

        $response = $this->actingAs($user)->get(route('foodnames.index'))
            ->assertSuccessful();

        $response->getOriginalContent()->each(function($foodname, $key) use($foodgroup) {
            $this->assertEquals($foodgroup->FoodGroupName, $foodname->foodgroup->FoodGroupName);
        });
    }
}
