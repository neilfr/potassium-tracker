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
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $searchText = $request->query('searchText');
        $conversionfactors = Conversionfactor::query()
            ->with('foodname')
            ->foodnameSearch($searchText)
            ->paginate(env('LOGENTRY_PAGINATION_PAGE_LENGTH'));
        return Inertia::render('Conversionfactors/Index', [
            'conversionfactors' => ConversionfactorResource::collection($conversionfactors),
        ]);
    }
}
