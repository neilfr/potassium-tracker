<?php

namespace App\Http\Controllers\Newlogentry;

use App\Http\Controllers\Controller;
use App\Http\Resources\NewlogentryResource;
use App\Models\Newlogentry;
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
        $logentries = Newlogentry::query()
            ->where('UserID', auth()->user()->id)
            ->paginate(env('LOGENTRY_PAGINATION_PAGE_LENGTH'));

        $portionAdjustedNutrientAmountsForLogentries = DB::table('newlogentries')
            ->join('newfoods','newlogentries.NewfoodID', '=', 'newfoods.NewfoodID')
            ->select(
                DB::raw('newfoods.KCalValue * newlogentries.portion as adjustedkcal'),
                DB::raw('newfoods.PotassiumValue * newlogentries.portion as adjustedpotassium')
            )
            ->where('newlogentries.UserID', '=', auth()->user()->id)
            ->get();

        $nutrientTotals = $portionAdjustedNutrientAmountsForLogentries->reduce(function($acc, $logentry) {
            return [
                'kcal' => $acc['kcal'] + $logentry->adjustedkcal,
                'k' => $acc['k'] + $logentry->adjustedpotassium,
            ];
        }, ['kcal' => 0, 'k' => 0]);

        return Inertia::render('Newlogentries/Index', [
            'logentries' => NewlogentryResource::collection($logentries),
            'kcalTotal' => $nutrientTotals['kcal'],
            'potassiumTotal' => $nutrientTotals['k'],
        ]);
    }
}
