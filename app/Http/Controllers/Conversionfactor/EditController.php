<?php

namespace App\Http\Controllers\Conversionfactor;

use App\Http\Controllers\Controller;
use App\Http\Resources\ConversionfactorResource;
use App\Http\Resources\FoodgroupResource;
use App\Models\Conversionfactor;
use App\Models\Foodgroup;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;

class EditController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */
    public function __invoke(Request $request, Conversionfactor $conversionfactor)
    {
        if ($conversionfactor->user_id !== auth()->user()->id){
            return response([], Response::HTTP_FORBIDDEN);
        }

        return Inertia::render('Conversionfactors/Edit',
            [
                'foodgroups' => FoodgroupResource::collection(Foodgroup::all()),
                'conversionfactor' => new ConversionfactorResource($conversionfactor),
            ]
        );
    }
}
