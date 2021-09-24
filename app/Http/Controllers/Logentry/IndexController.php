<?php

namespace App\Http\Controllers\Logentry;

use App\Http\Controllers\Controller;
use App\Http\Resources\LogentryResource;
use App\Models\Logentry;
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
        $logentries = LogentryResource::collection(
            Logentry::query()
            ->where('UserID', Auth::user()->id)
            ->with('conversionfactor')
            ->get()
        );

        return Inertia::render('Logentries/Index', [
            'logentries' => $logentries,
            'nutrienttotals'=> [
                'data' => $this->getNutrientTotalsForLogEntryCollection($logentries),
             ]
        ]);
    }

    private function getNutrientTotalsForLogEntryCollection($logentryCollection)
    {
        return $logentryCollection->reduce(function($acc, $logentry) {
            $resolved = $logentry->resolve();
            $acc['K'] += $resolved['K'];
            $acc['KCAL'] += $resolved['KCAL'];
            return $acc;
        }, [
            'KCAL' => 0,
            'K' => 0,
        ]);
    }
}
