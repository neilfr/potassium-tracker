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
        $favouriteFilter = $request->query('favouriteFilter');
        $conversionfactors = Conversionfactor::query()
            ->forAuthUser()
            ->with('foodname')
            ->foodnameSearch($searchText)
            ->favouriteFilter($favouriteFilter)
            ->paginate(env('LOGENTRY_PAGINATION_PAGE_LENGTH'));
        return Inertia::render('Conversionfactors/Index', [
            'conversionfactors' => ConversionfactorResource::collection($conversionfactors),
        ]);
    }
}
