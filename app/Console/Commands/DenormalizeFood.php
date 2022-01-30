<?php

namespace App\Console\Commands;

use App\Models\Food;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DenormalizeFood extends Command
{
    /**
     * The name and signature of the console command.
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
        $base = DB::table('favourites')
            ->rightJoin('conversionfactors', 'favourites.ConversionFactorID', '=', 'conversionfactors.id')->join('measurenames', 'measurenames.MeasureID', '=', 'conversionfactors.MeasureID')
            ->join('foodnames', 'foodnames.FoodID', '=', 'conversionfactors.FoodID')
            ->join('foodgroups', 'foodgroups.FoodGroupID','=','foodnames.FoodGroupID')
            ->where('conversionfactors.user_id','=', null)
            ->orWhere('conversionfactors.user_id', '=', 1)
            ->select(
                'conversionfactors.id as ConversionFactorID',
                'conversionfactors.user_id as UserID',
                'conversionfactors.FoodID',
                'foodgroups.FoodGroupID',
                'conversionfactors.MeasureID',
                'conversionfactors.ConversionFactorValue',
                'foodnames.FoodDescription',
                'foodgroups.FoodGroupName',
                'measurenames.MeasureDescription',
                DB::raw('favourites.user_id is not null as Favourite'),
            );

        $withKCal = DB::table('nutrientamounts')
            ->joinSub(
                $base, 'base', function ($join) {
                $join->on('nutrientamounts.FoodID', '=', 'base.FoodID');
            })
            ->join('nutrientnames', 'nutrientnames.NutrientID','=','nutrientamounts.NutrientID')
            ->where('nutrientamounts.NutrientID','=',208)
            ->select(
                'base.ConversionFactorID',
                'base.UserID',
                'base.FoodGroupID',
                'base.FoodID',
                'base.MeasureID',
                'base.ConversionFactorValue',
                'base.FoodDescription',
                'base.FoodGroupName',
                'base.MeasureDescription',
                'base.Favourite',
                DB::raw('nutrientamounts.NutrientValue * base.ConversionFactorValue as KCalValue'),
                'nutrientnames.NutrientUnit as KCalUnit',
                'nutrientnames.NutrientSymbol as KCalSymbol',
                'nutrientnames.NutrientName as KCalName',
            );

        $withPotassium = DB::table('nutrientamounts')
            ->joinSub(
                $withKCal, 'withKCal', function ($join) {
                $join->on('nutrientamounts.FoodID', '=', 'withKCal.FoodID');
            })
            ->join('nutrientnames', 'nutrientnames.NutrientID','=','nutrientamounts.NutrientID')
            ->where('nutrientamounts.NutrientID','=',306)
            ->select(
                'withKCal.ConversionFactorID',
                'withKCal.UserID',
                'withKCal.FoodID',
                'withKCal.FoodGroupID',
                'withKCal.MeasureID',
                'withKCal.Favourite',
                'withKCal.FoodDescription',
                'withKCal.FoodGroupName',
                'withKCal.MeasureDescription',
                'withKCal.ConversionFactorValue',
                'withKCal.KCalValue',
                'withKCal.KCalSymbol',
                'withKCal.KCalName',
                'withKCal.KCalUnit',
                DB::raw('nutrientamounts.NutrientValue * withKCal.ConversionFactorValue as PotassiumValue'),
                'nutrientnames.NutrientSymbol as PotassiumSymbol',
                'nutrientnames.NutrientName as PotassiumName',
                'nutrientnames.NutrientUnit as PotassiumUnit',
                DB::raw('withKCal.KCalValue / (nutrientamounts.NutrientValue * withKCal.ConversionFactorValue) as NutrientDensity')
            )
            ->get();

        $this->withProgressBar(
            $withPotassium,
            function($food) {
                Food::factory()->create([
                    "ConversionFactorID" => $food->ConversionFactorID,
                    "UserID" => $food->UserID,
                    "FoodID" => $food->FoodID,
                    "FoodGroupID" => $food->FoodGroupID,
                    "MeasureID" => $food->MeasureID,
                    "Favourite" => $food->Favourite,
                    "FoodDescription" => $food->FoodDescription,
                    "FoodGroupName" => $food->FoodGroupName,
                    "MeasureDescription" => $food->MeasureDescription,
                    "ConversionFactorValue" => $food->ConversionFactorValue,
                    "KCalValue" => $food->KCalValue,
                    "KCalSymbol" => $food->KCalSymbol,
                    "KCalName" => $food->KCalName,
                    "KCalUnit" => $food->KCalUnit,
                    "PotassiumValue" => $food->PotassiumValue,
                    "PotassiumSymbol" => $food->PotassiumSymbol,
                    "PotassiumName" => $food->PotassiumName,
                    "PotassiumUnit" => $food->PotassiumUnit,
                    "NutrientDensity" => $food->NutrientDensity,
                ]);
            }
        );

    }
}
