<?php

namespace App\Http\Controllers\Logentry;

use App\Http\Controllers\Controller;
use App\Models\Logentry;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Logentry $logentry, Request $request)
    {
        $logentry->update([
            'ConsumedAt' => $request->input('ConsumedAt'),
        ]);

        return redirect()->route('logentries.index');
    }
}
