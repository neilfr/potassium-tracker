<?php

namespace App\Http\Controllers\Logentry;

use App\Http\Controllers\Controller;
use App\Http\Resources\LogentryResource;
use App\Models\Logentry;
use App\Models\Nutrientname;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $paginatedLogentries = LogentryResource::collection(
            Logentry::query()
                ->where('UserID', Auth::user()->id)
                ->inDateRange($request->query('from'), $request->query('to'))
                ->orderBy('ConsumedAt', 'desc')
                ->paginate(env('LOGENTRY_PAGINATION_PAGE_LENGTH'))
        );

        $allLogentries = LogentryResource::collection(
            Logentry::query()
                ->where('UserID', Auth::user()->id)
                ->inDateRange($request->query('from'), $request->query('to'))
                ->get()
        );

        $allLogentriesWithPortionedNutrients = $allLogentries->map(function($logentry){
            return $logentry->conversionfactor->nutrients->map(function($nutrient) use($logentry) {
               return array_merge($nutrient,[
                   'scaled' => $nutrient['NutrientAmount'] * $logentry->portion/100,
               ]);
            });
        })->flatten(1);

        $uniqueNutrientIds = $allLogentriesWithPortionedNutrients
            ->whereIn('NutrientID', collect(explode(',', env('NUTRIENTS'))))
            ->pluck('NutrientID')
            ->unique();
        $nutrientModels = Nutrientname::query()
            ->whereIn('NutrientID', explode(',', env('NUTRIENTS', false)))
            ->get();

        $nutrientTotals = $uniqueNutrientIds->map( function ($uniqueNutrientId) use($allLogentriesWithPortionedNutrients, $nutrientModels) {
            $nutrientTotal = $allLogentriesWithPortionedNutrients->reduce(function ($acc, $nutrient) use($uniqueNutrientId) {
                return $uniqueNutrientId === $nutrient['NutrientID']
                    ? $acc + $nutrient['scaled']
                    : $acc;
            }, 0);

            return array_merge(
                $nutrientModels
                    ->where('NutrientID', $uniqueNutrientId)
                    ->first()
                    ->toArray(),
                ['total' => $nutrientTotal]
            );
        });

        return Inertia::render('Logentries/Index', [
            'page' => $paginatedLogentries->currentPage(),
            'logentries' => $paginatedLogentries,
            'nutrienttotals'=> [
                'data' => $nutrientTotals,
             ]
        ]);
    }

}
