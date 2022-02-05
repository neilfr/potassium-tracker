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
        $paginatedLogentries = Newlogentry::query()
            ->where('UserID', auth()->user()->id)
            ->inDateRange($request->query('from'), $request->query('to'))
            ->paginate(env('LOGENTRY_PAGINATION_PAGE_LENGTH'));

        $alllogentries = Newlogentry::query()
            ->where('UserID', auth()->user()->id)
            ->inDateRange($request->query('from'), $request->query('to'))
            ->get();

        $nutrientTotals = $alllogentries->reduce(function($acc, $logentry){
            return [
                'kcal' => $acc['kcal'] + $logentry->newfood->KCalValue * $logentry->portion / 100,
                'k' => $acc['k'] + $logentry->newfood->PotassiumValue * $logentry->portion / 100,
            ];
        }, ['kcal' => 0, 'k' => 0]);

        return Inertia::render('Newlogentries/Index', [
            'logentries' => NewlogentryResource::collection($paginatedLogentries),
            'kcalTotal' => $nutrientTotals['kcal'],
            'potassiumTotal' => $nutrientTotals['k'],
        ]);
    }
}
