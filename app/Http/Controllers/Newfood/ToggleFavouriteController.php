<?php

namespace App\Http\Controllers\Newfood;

use App\Http\Controllers\Controller;
use App\Models\Newfood;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ToggleFavouriteController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Newfood $food, Request $request)
    {
//        $favourite = Favourite::where('user_id', '=', auth()->user()->id)
//            ->where('ConversionFactorID', '=', $food->ConversionFactorID)
//            ->first();
//
//        if ($favourite)
//        {
//            $favourite->delete();
//        } else {
//            Favourite::factory()->create([
//               'ConversionFactorID' => $food->ConversionFactorID,
//               'user_id' => auth()->user()->id
//            ]);
//        };
        $user=User::find(auth()->user()->id);
//        dd($user->foodfavourites);

        if ($user->foodfavourites->contains($food)) {
            $user->foodfavourites()->detach($food);
        } else {
            $user->foodfavourites()->attach($food);
        };

        return redirect()->back();
    }
}
