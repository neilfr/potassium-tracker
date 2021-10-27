<?php

namespace App\Http\Controllers\Logentry;

use App\Http\Controllers\Controller;
use App\Models\Logentry;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Request $request)
    {
        Logentry::create([
            'UserID' => auth()->user()->id,
            'ConversionFactorID' => $request->input('id'),
            'ConsumedAt' => now()->toDateString(),
        ]);

        return redirect()->route('logentries.index');
    }
}
