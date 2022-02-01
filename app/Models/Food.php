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

}
