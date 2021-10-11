<?php

namespace App\Http\Controllers\Conversionfactor;

use App\Http\Controllers\Controller;
use App\Models\Conversionfactor;
use App\Models\User;
use Illuminate\Http\Request;

class ToggleFavouriteController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Conversionfactor $conversionfactor, Request $request)
    {
        $user=User::find(auth()->user()->id);

        if ($user->favourites->contains($conversionfactor)) {
            $user->favourites()->detach($conversionfactor);
        } else {
            $user->favourites()->attach($conversionfactor);
        };
        return redirect()->back();
//        return redirect()->route('conversionfactors.index');

    }
}
