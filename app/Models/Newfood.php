<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Newfood extends Model
{
    use HasFactory;

    protected $table = 'newfoods';

    protected $primaryKey = 'NewfoodID';

    public function scopeFavouriteFilter(Builder $query, $favouritefilter)
    {
        if ($favouritefilter==='yes') {
            $favouriteIds = User::find(auth()
                ->user()->id)
                ->foodfavourites()->pluck('newfoods.NewfoodID');
            $query->whereIn('NewfoodID', $favouriteIds);;
        }
    }

    public function scopeNewfoodSearch(Builder $query, ?string $searchText = null)
    {
        if (is_null($searchText)) {
            return $query;
        }

        $query->where(function($query) use ($searchText) {
            $terms = collect(array_map('trim',explode(',', $searchText)));
            $terms->each(function($term) use($query) {
                $query->where('FoodDescription', 'like', "%$term%" );
            });
        });
    }

    public function scopeOrderBySortOrder(Builder $query, $sortOrder)
    {
        switch ($sortOrder) {
            case 'density-des':
                $query->orderByDesc('NutrientDensity');
                break;
            case 'density-asc':
                $query->orderBy('NutrientDensity');
                break;
            case 'food-description-asc':
                $query->orderBy('FoodDescription');
                break;
            case 'food-description-des':
                $query->orderByDesc('FoodDescription');
                break;
            default:
                break;
        }
    }

    public function getFavouriteAttribute()
    {
        return User::find(auth()->user()->id)
            ->foodfavourites()
            ->where('food_favourites.NewfoodID', $this->NewfoodID)
            ->exists();
    }

    public function getEditableAttribute()
    {
        return $this->UserID === auth()->user()->id;
    }

    public function foodgroup()
    {
        return $this->belongsTo(Foodgroup::class, 'FoodGroupID', 'FoodGroupID');
    }


}
