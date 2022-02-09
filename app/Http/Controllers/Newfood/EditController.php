<?php

namespace App\Http\Controllers\Newfood;

use App\Http\Controllers\Controller;
use App\Http\Resources\FoodgroupResource;
use App\Http\Resources\NewfoodResource;
use App\Models\Foodgroup;
use App\Models\Newfood;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EditController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param Newfood $food
     * @param \Illuminate\Http\Request $request
     * @return \Inertia\Response
     */
    public function __invoke(Newfood $food, Request $request)
    {
        return Inertia::render('Foods/Edit',
            [
                'food' => new NewfoodResource($food),
                'foodgroups' => FoodgroupResource::collection(Foodgroup::all())
            ]
        );
    }
}
