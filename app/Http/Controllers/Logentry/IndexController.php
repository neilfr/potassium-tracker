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
        $logentries = LogentryResource::collection(
            Logentry::query()
                ->where('UserID', Auth::user()->id)
//                ->inDateRange('2021-09-18', '2021-09-18')
                ->inDateRange($request->query('from'), $request->query('to'))
                ->get()
        );

        $nutrientsForAllLogentries = collect($logentries->resolve())->pluck('nutrients')->flatten(1);
        $uniqueNutrientIds = $nutrientsForAllLogentries
            ->whereIn('NutrientID', collect(explode(',', env('NUTRIENTS'))))
            ->pluck('NutrientID')
            ->unique();

        $nutrientModels = Nutrientname::query()
            ->whereIn('NutrientID', explode(',', env('NUTRIENTS', false)))
            ->get();

        $nutrientTotals = $uniqueNutrientIds->map( function ($uniqueNutrientId) use($nutrientsForAllLogentries, $nutrientModels) {
            $nutrientTotal = $nutrientsForAllLogentries->reduce(function ($acc, $nutrient) use($uniqueNutrientId) {
                return $uniqueNutrientId === $nutrient['NutrientID']
                    ? $acc + $nutrient['NutrientAmount']
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
            'logentries' => $logentries,
            'nutrienttotals'=> [
                'data' => $nutrientTotals,
             ]
        ]);
    }

}
