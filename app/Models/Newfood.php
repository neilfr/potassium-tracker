<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class Newfood extends Model
{
    use HasFactory;

    protected $table = 'newfoods';

    protected $primaryKey = 'NewfoodID';
//
//    public function scopeFavouriteFilter(Builder $query, $favouritefilter)
//    {
//        if ($favouritefilter==='yes') {
//            $favouriteIds = User::find(auth()
//                ->user()->id)
//                ->foodfavourites()->pluck('newfoods.NewfoodID');
//            $query->whereIn('NewfoodID', $favouriteIds);;
//        }
//    }
//
//    public function scopeNewfoodSearch(Builder $query, ?string $searchText = null)
//    {
//        if (is_null($searchText)) {
//            return $query;
//        }
//
//        $query->where(function($query) use ($searchText) {
//            $terms = collect(array_map('trim',explode(',', $searchText)));
//            $terms->each(function($term) use($query) {
//                $query->where('FoodDescription', 'like', "%$term%" );
//            });
//        });
//    }
//
//    public function getFavouriteAttribute()
//    {
//return DB::table('food_favourites')
//    ->where('UserID', '=', auth()->user()->id)
//    ->where('food_favourites.NewfoodID', '=', $this->NewfoodID)
//    ->exists();

//        $taco = User::find(auth()->user()->id)
//            ->foodfavourites()
//            ->where('food_favourites.NewfoodID', $this->NewfoodID)
//            ->get();
//        dd('taco',$taco, 'truth', User::find(auth()->user()->id)
//            ->foodfavourites()
//            ->where('newfoods.NewfoodID', $this->NewfoodID)
//            ->exists());
//        return User::find(auth()->user()->id)
//            ->foodfavourites()
//            ->where('food_favourites.NewfoodID', $this->NewfoodID)
//            ->exists();
//        dd('taco', $taco->pluck('NewfoodID'));
//        return true;
//    }

    public function getEditableAttribute()
    {
        return $this->UserID === auth()->user()->id;
    }


}
