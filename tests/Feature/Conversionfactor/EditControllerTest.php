<?php

namespace Tests\Feature\Conversionfactor;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\TestHelpers;

class EditControllerTest extends TestCase
{
    use RefreshDatabase, TestHelpers;

    /** @test */
    public function it_can_edit_a_conversionfactor_the_user_owns()
    {
        $user = User::factory()->create();
        $nutrients = $this->createNutrients();
        $userConversionfactorData = $this->createConversionFactor($nutrients, $user->id);

        $this->actingAs($user)->get(route('conversionfactors.edit', $userConversionfactorData[0]['ConversionFactorID']))
            ->assertSuccessful();
    }

    /** @test */
    public function it_cannot_edit_a_conversionfactor_the_user_does_not_own()
    {
        $user = User::factory()->create();
        $nutrients = $this->createNutrients();
        $userConversionfactorData = $this->createConversionFactor($nutrients, $user->id);
        $anotherUser = User::factory()->create();
        $anotherUserConversionfactorData = $this->createConversionFactor($nutrients, $anotherUser->id);

        $this->actingAs($user)->get(route('conversionfactors.edit', $anotherUserConversionfactorData[0]['ConversionFactorID']))
            ->assertForbidden();
    }
}
