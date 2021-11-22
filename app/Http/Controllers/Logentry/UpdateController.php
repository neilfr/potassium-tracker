<?php

namespace App\Http\Controllers\Logentry;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateLogentryRequest;
use App\Models\Logentry;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Logentry $logentry, UpdateLogentryRequest $request)
    {
        $logentry->update($request->validated());

        return redirect()->back();
    }
}
