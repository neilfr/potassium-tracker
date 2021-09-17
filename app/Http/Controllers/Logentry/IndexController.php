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
        $logentries =
            Logentry::query()
            ->where('UserID', Auth::user()->id)
            ->get();
        $foo=LogentryResource::collection($logentries);
//        dd($foo);
//dd($logentries[0]->conversionfactor->foodname);
//        dd(LogentryResource::collection($logentries));
        return Inertia::render('Logentries/Index', [
            'logentries' => $foo]
        );
    }
}
