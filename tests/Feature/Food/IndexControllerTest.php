<?php

namespace Tests\Feature\Food;

use App\Models\Food;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class IndexControllerTest extends TestCase
{
    /** @test */
    public function it_can_test()
    {
        $food = Food::factory()->create();
        dd('food', $food);

    }

}
