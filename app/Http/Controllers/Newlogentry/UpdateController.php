<?php

namespace App\Http\Controllers\Newlogentry;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateNewlogentryRequest;
use App\Models\Newlogentry;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke($id, UpdateNewlogentryRequest $request)
    {
        $newlogentry = Newlogentry::find($id);
        $newlogentry->update($request->validated());

        return redirect()->back();
    }
}
