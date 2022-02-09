<?php

namespace App\Http\Controllers\Newfood;

use App\Http\Controllers\Controller;
use App\Http\Resources\NewfoodResource;
use App\Models\Newfood;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class IndexController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */
    public function __invoke(Request $request)
    {
        $searchText = $request->query('searchText');
        $favouritefilter = $request->query('favouritefilter') ?: 'yes';
        $sortOrder = $request->query('sortOrder');

        $user = User::find(auth()->user()->id);
        if(in_array($sortOrder,['density-asc', 'density-des', 'food-description-asc', 'food-description-des'])){
            $user->newfoodsort = $sortOrder;
            $user->save();
        } else {
            $sortOrder = User::find(auth()->user()->id)->newfoodsort;
        }
//$sortOrder = 'density-des';
        $foods = Newfood::query()
            ->favouriteFilter($favouritefilter)
            ->newfoodSearch($searchText)
            ->orderBySortOrder($sortOrder)
            ->paginate(env('LOGENTRY_PAGINATION_PAGE_LENGTH'));
        return Inertia::render('Foods/Index', [
            'foods' => NewfoodResource::collection($foods),
            'favouritefilter' => $favouritefilter,
            'sortorder' => $sortOrder
        ]);
    }
}
