<?php

namespace App\Http\Controllers\Newlogentry;

use App\Http\Controllers\Controller;
use App\Models\Newlogentry;
use Illuminate\Http\Request;

class DestroyController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke($logentryId, Request $request)
    {
        $logentry = Newlogentry::find($logentryId);
        $logentry->delete();

        return redirect()->back();
    }
}
