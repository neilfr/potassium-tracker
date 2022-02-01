<?php

namespace App\Http\Controllers\Food;

use App\Http\Controllers\Controller;
use App\Http\Resources\FoodResource;
use App\Models\Favourite;
use App\Models\Food;
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
        $searchText = $request->query('searchText');
        $favouritefilter = $request->query('favouritefilter') ?: 'yes';

        $foods = Food::query()
            ->favouriteFilter($favouritefilter)
            ->orderByDesc('NutrientDensity')
            ->paginate(env('LOGENTRY_PAGINATION_PAGE_LENGTH'));

//        if($favouritefilter==='yes'){
//            $query = DB::table('favourites')
//                ->join('foods', 'favourites.ConversionFactorID', '=', 'foods.ConversionFactorID');
//        } else {
//            $query = DB::table('favourites')
//                ->rightJoin('foods', 'favourites.ConversionFactorID', '=', 'foods.ConversionFactorID');
//        }

//        $foods = $query
//            ->where('foods.UserID','=', null)
//            ->orWhere('foods.UserID', '=', auth()->user()->id)
//            ->select(
//                'foods.id',
//                'foods.UserID',
//                'foods.FoodID',
//                'foods.FoodGroupID',
//                'foods.MeasureID',
//                'foods.ConversionFactorID',
//                DB::raw('favourites.user_id is not null as Favourite'),
//                'foods.FoodDescription',
//                'foods.FoodGroupName',
//                'foods.MeasureDescription',
//                'foods.ConversionFactorValue',
//                'foods.KCalValue',
//                'foods.KCalSymbol',
//                'foods.KCalName',
//                'foods.KCalUnit',
//                'foods.PotassiumValue',
//                'foods.PotassiumSymbol',
//                'foods.PotassiumName',
//                'foods.PotassiumUnit',
//                'foods.NutrientDensity',
//            )
//            ->orderBy('NutrientDensity','desc')
//            ->paginate(env('LOGENTRY_PAGINATION_PAGE_LENGTH'));

        return Inertia::render('Foods/Index', [
           'foods' => FoodResource::collection($foods),
            'favouritefilter' => $favouritefilter,
        ]);
    }
}
