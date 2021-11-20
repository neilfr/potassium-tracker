<?php

namespace App\Http\Controllers\Conversionfactor;

use App\Http\Controllers\Controller;
use App\Models\Conversionfactor;
use App\Models\Logentry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DestroyController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Request $request, Conversionfactor $conversionfactor)
    {
        if ($conversionfactor->user_id !== auth()->user()->id){
            return;
        }

        if (Logentry::where('ConversionFactorID', $conversionfactor->id)->exists()){
            return;
        }

        $measurename = $conversionfactor->measurename;
        $foodname = $conversionfactor->foodname;

        $foodname->nutrientnames->each(function($nutrientname) use($foodname){
            $foodname->nutrientnames()->detach($nutrientname);
        });
        DB::table('favourites')->where('ConversionFactorID', $conversionfactor->id)->delete();
        $conversionfactor->delete();
        $measurename->delete();
        $foodname->delete();

        return redirect()->route('conversionfactors.index');
    }
}
