<?php

namespace App\Http\Controllers\Conversionfactor;

use App\Http\Controllers\Controller;
use App\Http\Resources\ConversionfactorResource;
use App\Models\Conversionfactor;
use App\Models\Favourite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class IndexController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */
    public function __invoke(Request $request)
    {
        $base = DB::table('conversionfactors')
            ->join('favourites', 'favourites.ConversionFactorID', '=', 'conversionfactors.id')
            ->join('measurenames', 'measurenames.MeasureID', '=', 'conversionfactors.MeasureID')
            ->join('foodnames', 'foodnames.FoodID', '=', 'conversionfactors.FoodID')
            ->where('conversionfactors.user_id','=', null)
            ->orWhere('conversionfactors.user_id', '=', auth()->user()->id)
            ->select(
                'conversionfactors.id as ConversionFactorID',
                'conversionfactors.FoodID',
                'conversionfactors.MeasureID',
                'conversionfactors.ConversionFactorValue',
                'foodnames.FoodDescription',
                'measurenames.MeasureDescription'
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
                'base.FoodID',
                'base.MeasureID',
                'base.ConversionFactorValue',
                'base.FoodDescription',
                'base.MeasureDescription',
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
                'withKCal.FoodID',
                'withKCal.MeasureID',
                'withKCal.FoodDescription',
                'withKCal.MeasureDescription',
                'withKCal.ConversionFactorValue',
                'withKCal.KCalValue',
                'withKCal.KCalSymbol',
                'withKCal.KCalName',
                'withKCal.KCalUnit',
                DB::raw('nutrientamounts.NutrientValue * withKCal.ConversionFactorValue as PotassiumValue'),
                'nutrientnames.NutrientUnit as PotassiumUnit',
                'nutrientnames.NutrientSymbol as PotassiumSymbol',
                'nutrientnames.NutrientName as PotassiumName',
                DB::raw('withKCal.KCalValue / (nutrientamounts.NutrientValue * withKCal.ConversionFactorValue) as NutrientDensity')
            )->orderBy('NutrientDensity','desc')
            ->paginate(env('LOGENTRY_PAGINATION_PAGE_LENGTH'));
        $searchText = $request->query('searchText');
        $favouritefilter = $request->query('favouritefilter') ?: 'yes';

        return Inertia::render('Conversionfactors/Index', [
            'conversionfactors' => $withPotassium,
            'favouritefilter' => $favouritefilter,
        ]);

//        return Inertia::render('Conversionfactors/Index', [
//            'conversionfactors' => ConversionfactorResource::collection($conversionfactors),
//            'favouritefilter' => $favouritefilter,
//        ]);
    }
}
