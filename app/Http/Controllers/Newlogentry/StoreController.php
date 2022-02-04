<?php

namespace App\Http\Controllers\Newlogentry;

use App\Http\Controllers\Controller;
use App\Models\Newlogentry;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        Newlogentry::create([
            'UserID' => auth()->user()->id,
            'NewfoodID' => $request->input('NewfoodID'),
            'portion' => 100,
            'ConsumedAt' => now()->toDateString(),
        ]);

        return redirect()->route('newlogentries.index');    }
}
