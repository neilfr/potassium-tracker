<?php

namespace Tests\Feature\Foodgroup;

use App\Models\Foodgroup;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_return_foodgroups_as_an_authenticated_user()
    {
        $user = User::factory()->create();
        $foodgroups = Foodgroup::factory()->count(10)->create();

        $response = $this->actingAs($user)->get(route('foodgroups.index'))
            ->assertSuccessful();

        $this->assertEquals($foodgroups->toArray(), $response->getOriginalContent());
    }

    /** @test */
    public function it_cannot_return_foodgroups_as_an_unauthenticated_user()
    {
        Foodgroup::factory()->count(10)->create();

        $response = $this->get(route('foodgroups.index'))
            ->assertRedirect();
    }
}
