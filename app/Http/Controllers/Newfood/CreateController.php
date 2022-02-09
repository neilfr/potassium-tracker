<?php

namespace App\Http\Controllers\Newfood;

use App\Http\Controllers\Controller;
use App\Http\Resources\FoodgroupResource;
use App\Models\Foodgroup;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CreateController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */
    public function __invoke(Request $request)
    {
        return Inertia::render('Foods/Create',
            [
                'foodgroups' => FoodgroupResource::collection(Foodgroup::all())
            ]
        );
    }
}
