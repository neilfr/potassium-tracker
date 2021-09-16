<?php

namespace Database\Seeders;

use App\Models\Conversionfactor;
use App\Models\Logentry;
use App\Models\User;
use Illuminate\Database\Seeder;

class LogentrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::query()
            ->where('email', 'test@example.com')
            ->first();

        $conversionfactors = Conversionfactor::all()->take(10);

        $conversionfactors->each(function ($conversionfactor) use ($user) {
            Logentry::factory()->create([
                'UserID' => $user->id,
                'ConversionFactorID' => $conversionfactor->id,
                'ConsumedAt' => now(),
            ]);
        });
    }
}
