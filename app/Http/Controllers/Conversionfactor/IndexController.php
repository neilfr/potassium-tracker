<?php

namespace App\Http\Controllers\Food;

use App\Http\Controllers\Controller;
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
        ['query'=>$query1, 'select' =>$foo] = $this->tacos($request->query('favouritefilter'));

        $base = $query1->join('measurenames', 'measurenames.MeasureID', '=', 'conversionfactors.MeasureID')
            ->join('foodnames', 'foodnames.FoodID', '=', 'conversionfactors.FoodID')
            ->where('conversionfactors.user_id','=', null)
            ->orWhere('conversionfactors.user_id', '=', auth()->user()->id)
            ->select(
                'conversionfactors.id as ConversionFactorID',
                'conversionfactors.FoodID',
                'conversionfactors.MeasureID',
                'conversionfactors.ConversionFactorValue',
                'foodnames.FoodDescription',
                'measurenames.MeasureDescription',
                $foo,
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
                'withKCal.FoodID',
                'withKCal.MeasureID',
                'withKCal.Favourite',
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
//        $searchText = $request->query('searchText');
        $favouritefilter = $request->query('favouritefilter') ?: 'yes';

        return Inertia::render('Conversionfactors/Index', [
            'conversionfactors' => $withPotassium,
            'favouritefilter' => $favouritefilter,
        ]);
    }

    public function tacos($queryParamFavourite)
    {
        if ($queryParamFavourite==='yes') {
            return [
                'query' => DB::table('favourites')
                    ->join('conversionfactors', 'favourites.ConversionFactorID', '=', 'conversionfactors.id'),
                'select' => DB::raw('1 as Favourite')
            ];
        }

        return [
            'query' => DB::table('favourites')
                ->rightJoin('conversionfactors', 'favourites.ConversionFactorID', '=', 'conversionfactors.id'),
            'select' => DB::raw('favourites.user_id is not null as Favourite')
        ];
    }

}
