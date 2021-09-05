<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NutrientConfigTest extends TestCase
{
    /** @test */
    public function it_can_retrieve_nutrients_from_config()
    {
        $nutrients = explode(',', env('NUTRIENTS'));

        $this->assertEquals($nutrients, [208,306]);
    }
}
