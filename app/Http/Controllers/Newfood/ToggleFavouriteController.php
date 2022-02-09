<?php

namespace App\Http\Controllers\Newfood;

use App\Http\Controllers\Controller;
use App\Models\Newfood;
use App\Models\User;
use Illuminate\Http\Request;

class ToggleFavouriteController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param Newfood $food
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Newfood $food, Request $request)
    {
        $user=User::find(auth()->user()->id);

        if ($user->foodfavourites->contains($food)) {
            $user->foodfavourites()->detach($food);
        } else {
            $user->foodfavourites()->attach($food);
        };

        return redirect()->back();
    }
}
