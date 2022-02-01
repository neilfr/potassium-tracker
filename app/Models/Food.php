<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Food extends Model
{
    use HasFactory;

    protected $table = 'foods';

    public function scopeFavouriteFilter(Builder $query, $favouritefilter)
    {
        if ($favouritefilter==='yes') {
            $favouriteIds = User::find(auth()
                ->user()->id)
                ->favourites()->pluck('ConversionFactorID');
            $query->whereIn('ConversionFactorID', $favouriteIds);;
        }
    }

    public function scopeFoodSearch(Builder $query, ?string $searchText = null)
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

    public function getFavouriteAttribute()
    {
        return User::find(auth()->user()->id)
            ->favourites()
            ->where('ConversionFactorID', $this->ConversionFactorID)
            ->exists();
    }

    public function getEditableAttribute()
    {
        return $this->UserID === auth()->user()->id;
    }


}
