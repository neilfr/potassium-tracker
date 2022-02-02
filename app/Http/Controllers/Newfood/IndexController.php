<?php

namespace App\Http\Controllers\Newfood;

use App\Http\Controllers\Controller;
use App\Http\Resources\FoodResource;
use App\Http\Resources\NewfoodResource;
use App\Models\Food;
use App\Models\Newfood;
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
        $foods = Newfood::query()
            ->favouriteFilter($favouritefilter)
            ->newfoodSearch($searchText)
            ->orderByDesc('NutrientDensity')
            ->paginate(env('LOGENTRY_PAGINATION_PAGE_LENGTH'));

        return Inertia::render('Foods/Index', [
            'foods' => NewfoodResource::collection($foods),
            'favouritefilter' => $favouritefilter,
        ]);
    }
}
