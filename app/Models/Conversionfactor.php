<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;
use phpDocumentor\Reflection\Types\Boolean;

class Conversionfactor extends Pivot
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $table='conversionfactors';

    protected $with = ['foodname','measurename'];

    public function foodname(){
        return $this->belongsTo(Foodname::class,'FoodID');
    }

    public function measurename(){
        return $this->belongsTo(Measurename::class,'MeasureID');
    }

    public function getNutrientsAttribute(){
        $nutrients = $this->foodname->nutrientnames
            ->whereIn('NutrientID', collect(explode(',', env('NUTRIENTS'))));
        return $nutrients->map( function($nutrient) {
            return array_merge($nutrient->toArray(), [
                'NutrientAmount' => $nutrient->pivot->NutrientValue * $this->ConversionFactorValue,
            ]);
        });
    }

    public function scopeFoodnameSearch(Builder $query, ?string $searchText = null)
    {
        if (is_null($searchText)) {
            return $query;
        }

        $query->whereHas('foodname', function($query) use ($searchText) {
            $terms = collect(explode(',', $searchText));
            $terms->each(function($term) use($query){
                $query->where('FoodDescription', 'like', "%$term%" );
            });
        });
    }

    public function scopeFavourite(Builder $query, $favourite)
    {
        if ($favourite!='true') {
            return $query;
        }

        $favouriteIds = User::find(auth()
            ->user()->id)
            ->favourites()->pluck('ConversionFactorID');
        $query->whereIn('id', $favouriteIds);
        return $query;
    }

    public function scopeFavouritesFilter(Builder $query, $favouritesFilter)
    {
        if (is_null($favouritesFilter) || $favouritesFilter==="no") {
            return $query;
        }
        if ($favouritesFilter==="yes") {
            $favouriteIds = User::find(auth()
                ->user()->id)
                ->favourites()->pluck('food_id');
            $query->whereIn('id', $favouriteIds);
        }
    }
}
