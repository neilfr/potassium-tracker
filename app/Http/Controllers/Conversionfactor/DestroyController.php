<?php

namespace App\Http\Controllers\Conversionfactor;

use App\Http\Controllers\Controller;
use App\Models\Conversionfactor;
use Illuminate\Http\Request;

class DestroyController extends Controller
{

    public function __invoke(Request $request, Conversionfactor $conversionfactor)
    {
        if ($conversionfactor->user_id === auth()->user()->id)
        {
            $measurename = $conversionfactor->measurename;
            $foodname = $conversionfactor->foodname;

            $foodname->nutrientnames->each(function($nutrientname) use($foodname){
                $foodname->nutrientnames()->detach($nutrientname);
            });
            $conversionfactor->delete();
            $measurename->delete();
            $foodname->delete();
        }
    }
}
