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
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $nutrientModels = Nutrientname::query()
            ->whereIn('NutrientID', explode(',', env('NUTRIENTS', false)))
            ->get()
            ->toArray();

        $nutrientModelsWithTotals = array_map(function($nutrient){
            return array_merge($nutrient, ['total' => 0]);
        }, $nutrientModels);

        $logentries = LogentryResource::collection(
            Logentry::query()
            ->where('UserID', Auth::user()->id)
            ->with(['conversionfactor.measurename','conversionfactor.foodname'])
            ->get()
        );

        $logentriesCollection = collect($logentries->resolve());

        $nutrientTotals = $logentriesCollection->reduce(function($acc, $logentry){
            $total = array_replace($acc[0],
                [
                    'total' => $acc[0]['total']
                        + $logentry['ConversionFactorValue']
                        * $logentry['NutrientNames'][0]->pivot->NutrientValue
                ]
            );

            return array_replace($acc,['0' => $total]);
        }, $nutrientModelsWithTotals);
dd($nutrientTotals);
        return Inertia::render('Logentries/Index', [
            'logentries' => $logentries,
//            'nutrienttotals'=> [
//                'data' => $this->getNutrientTotalsForLogEntryCollection($logentries),
//             ]
        ]);
    }

//    private function getNutrientTotalsForLogEntryCollection($logentryCollection)
//    {
//        return $logentryCollection->reduce(function($acc, $logentry) {
//            $resolved = $logentry->resolve();
//            $acc['K'] += $resolved['K'];
//            $acc['KCAL'] += $resolved['KCAL'];
//            return $acc;
//        }, [
//            'KCAL' => 0,
//            'K' => 0,
//        ]);
//    }
}
