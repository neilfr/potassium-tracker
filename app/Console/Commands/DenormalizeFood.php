<?php

namespace App\Console\Commands;

use App\Models\Favourite;
use App\Models\FoodFavourite;
use App\Models\Newfood;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DenormalizeFood extends Command
{
    /**
     * The name and signature of the console command.
     *
     *
     * @var string
     */
    protected $signature = 'denormalize:food';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Denormalize food';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->createDenormalizedFoods();
        $this->migrateFavourites();
    }

    protected function createDenormalizedFoods(): void
    {
        $this->info('Retrieving food data');
        $base = DB::table('conversionfactors')
            ->join('measurenames', 'measurenames.MeasureID', '=', 'conversionfactors.MeasureID')
            ->join('foodnames', 'foodnames.FoodID', '=', 'conversionfactors.FoodID')
            ->where('conversionfactors.user_id', '=', null)
            ->orWhere('conversionfactors.user_id', '=', 14)
            ->select(
                'conversionfactors.id as ConversionFactorID',
                'conversionfactors.user_id as UserID',
                'conversionfactors.FoodID',
                'foodnames.FoodGroupID',
                'conversionfactors.MeasureID',
                'conversionfactors.ConversionFactorValue',
                'foodnames.FoodDescription',
                'measurenames.MeasureDescription',
            );

        $withKCal = DB::table('nutrientamounts')
            ->joinSub(
                $base, 'base', function ($join) {
                $join->on('nutrientamounts.FoodID', '=', 'base.FoodID');
            })
            ->join('nutrientnames', 'nutrientnames.NutrientID', '=', 'nutrientamounts.NutrientID')
            ->where('nutrientamounts.NutrientID', '=', 208)
            ->select(
                'base.ConversionFactorID',
                'base.UserID',
                'base.FoodGroupID',
                'base.FoodID',
                'base.MeasureID',
                'base.ConversionFactorValue',
                'base.FoodDescription',
                'base.MeasureDescription',
                DB::raw('nutrientamounts.NutrientValue * base.ConversionFactorValue as KCalValue'),
            );

        $this->info('Populating denormalized foods table');
        $withPotassium = DB::table('nutrientamounts')
            ->joinSub(
                $withKCal, 'withKCal', function ($join) {
                $join->on('nutrientamounts.FoodID', '=', 'withKCal.FoodID');
            })
            ->join('nutrientnames', 'nutrientnames.NutrientID', '=', 'nutrientamounts.NutrientID')
            ->where('nutrientamounts.NutrientID', '=', 306)
            ->select(
                'withKCal.ConversionFactorID',
                'withKCal.UserID',
                'withKCal.FoodID',
                'withKCal.FoodGroupID',
                'withKCal.MeasureID',
                'withKCal.FoodDescription',
                'withKCal.MeasureDescription',
                'withKCal.ConversionFactorValue',
                'withKCal.KCalValue',
                DB::raw('nutrientamounts.NutrientValue * withKCal.ConversionFactorValue as PotassiumValue'),
                DB::raw('withKCal.KCalValue / (nutrientamounts.NutrientValue * withKCal.ConversionFactorValue) as NutrientDensity')
            )
            ->get();

        $this->withProgressBar(
            $withPotassium,
            function ($food) {
                Newfood::factory()->create([
                    "ConversionFactorID" => $food->ConversionFactorID,
                    "UserID" => $food->UserID,
                    "FoodID" => $food->FoodID,
                    "FoodGroupID" => $food->FoodGroupID,
                    "MeasureID" => $food->MeasureID,
                    "FoodDescription" => $food->FoodDescription,
                    "MeasureDescription" => $food->MeasureDescription,
                    "KCalValue" => $food->KCalValue,
                    "PotassiumValue" => $food->PotassiumValue,
                    "NutrientDensity" => $food->NutrientDensity,
                ]);
            }
        );
    }

    protected function migrateFavourites(): void
    {
        $this->info('Retrieving favourites data');
        $favourites = Favourite::all();

        $this->info('Populating new food_favourites table');
        $this->withProgressBar($favourites, function ($favourite) {
            $food = Newfood::where('ConversionFactorID', '=', $favourite->ConversionFactorID)
                ->first();
            FoodFavourite::factory()->create([
                'UserID' => $favourite->user_id,
                'NewfoodID' => $food->NewfoodID,
            ]);
        });
    }
}
