<?php

namespace Database\Seeders;

use App\Models\Conversionfactor;
use App\Models\Favourite;
use App\Models\Logentry;
use App\Models\User;
use Illuminate\Database\Seeder;

class FavouritesSeeder extends Seeder
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

        $conversionfactors = Conversionfactor::limit(30)->skip(2)->get();

        $conversionfactors->each(function ($conversionfactor) use ($user) {
            Favourite::factory()->create([
                'user_id' => $user->id,
                'ConversionFactorID' => $conversionfactor->id,
            ]);
        });
    }
}
