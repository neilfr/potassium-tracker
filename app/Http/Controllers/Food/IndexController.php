<?php

namespace App\Http\Controllers\Food;

use App\Http\Controllers\Controller;
use App\Http\Resources\FoodResource;
use App\Models\Favourite;
use App\Models\Food;
use Illuminate\Http\Request;
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
        $foods = Food::query()->orderByDesc('NutrientDensity')
            ->paginate(env('LOGENTRY_PAGINATION_PAGE_LENGTH'));
//        dd('favourites', Favourite::all());
//        $foodFavourites = Food::query()
//            ->join('favourites', 'foods.ConversionFactorID', '=', 'favourites.ConversionFactorID')
//            ->where('favourites.user_id', '=', auth()->user()->id)
//            ->paginate(env('LOGENTRY_PAGINATION_PAGE_LENGTH'));
//        dd('foodfavourites', $foodFavourites);
        return Inertia::render('Foods/Index', [
           'foods' => FoodResource::collection($foods),
            'favouritefilter' => $favouritefilter,
        ]);
    }
}