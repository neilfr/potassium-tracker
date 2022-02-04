<?php

namespace App\Http\Controllers\Newlogentry;

use App\Http\Controllers\Controller;
use App\Http\Resources\NewlogentryResource;
use App\Models\Newlogentry;
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
        $newlogentries = Newlogentry::all();

        return Inertia::render('Newlogentries/Index', [
            'logentries' => NewlogentryResource::collection($newlogentries),
        ]);
    }
}
