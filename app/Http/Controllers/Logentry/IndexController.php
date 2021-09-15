<?php

namespace App\Http\Controllers\Logentry;

use App\Http\Controllers\Controller;
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
        $logentries = Logentry::query()
            ->where('UserID', Auth::user()->id)
            ->get();
        return Inertia::render('Logentries/Index', ['logentries' => $logentries->toArray()]);
    }
}
