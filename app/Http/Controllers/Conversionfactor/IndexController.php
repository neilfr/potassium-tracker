<?php

namespace App\Http\Controllers\Conversionfactor;

use App\Http\Controllers\Controller;
use App\Http\Resources\ConversionfactorResource;
use App\Models\Conversionfactor;
use App\Models\User;
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
        $conversionfactors = Conversionfactor::query()
            ->userOwnedOrShared()
            ->with('foodname')
            ->foodnameSearch($searchText)
            ->favouriteFilter($favouritefilter)
            ->paginate(env('LOGENTRY_PAGINATION_PAGE_LENGTH'))
            ->sortBy('NutrientDensity["value"]');
        // not sorting correctly!!!
dd('conversionfactors', $conversionfactors[0]->NutrientDensity, $conversionfactors[1]->NutrientDensity);
        return Inertia::render('Conversionfactors/Index', [
            'conversionfactors' => ConversionfactorResource::collection($conversionfactors),
            'favouritefilter' => $favouritefilter,
        ]);
    }
}
