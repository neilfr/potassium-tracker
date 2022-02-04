<?php

namespace App\Http\Controllers\Newfood;

use App\Http\Controllers\Controller;
use App\Models\Newfood;
use Illuminate\Http\Request;

class DestroyController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Newfood $food, Request $request)
    {
        if ($food->UserID !== auth()->user()->id){
            return;
        }

        // TODO: prevent deletion if food is used in a log
//        if (Logentry::where('ConversionFactorID', $conversionfactor->id)->exists()){
//            return redirect()->back()->withErrors([
//                'logExists' => 'Food cannot be deleted as there is a log entry for it.  To delete this food, first delete all of its related log entries.',
//            ]);
//        }

        auth()->user()->foodfavourites()->detach($food->NewfoodID);

        $food->delete();

        return redirect()->route('foods.index');
    }
}
