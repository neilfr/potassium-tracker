<?php

namespace App\Http\Controllers\Food;

use App\Http\Controllers\Controller;
use App\Models\Favourite;
use App\Models\Food;
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
    public function __invoke(Food $food, Request $request)
    {
        $favourite = Favourite::where('user_id', '=', auth()->user()->id)
            ->where('ConversionFactorID', '=', $food->ConversionFactorID)
            ->first();

        if ($favourite)
        {
            $favourite->delete();
        } else {
            Favourite::factory()->create([
               'ConversionFactorID' => $food->ConversionFactorID,
               'user_id' => auth()->user()->id
            ]);
        };

        return redirect()->back();
    }
}
