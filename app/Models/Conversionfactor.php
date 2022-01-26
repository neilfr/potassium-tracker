<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        $nutrientConfig = collect(explode(',', env('NUTRIENTS')));

        return $nutrientConfig->map(function ($n, $i) {
            $nutrientExists = $this->foodname->nutrientnames->where('NutrientID', $n)->first();
            if($nutrientExists){
                return array_merge($nutrientExists->toArray(), [
                    'NutrientAmount' => $nutrientExists->pivot->NutrientValue * $this->ConversionFactorValue,
                ]);
            } else {
                return array_merge(Nutrientname::where('NutrientID', $n)->first()->toArray(), [
                    'pivot' => [
                        'FoodID' => $this->foodname->FoodID,
                        'NutrientID' => $n,
                        'NutrientValue' => 'NA',
                    ],
                    'NutrientAmount' => 'NA'
                ]);
            }
        });
    }

    public function getNutrientDensityAttribute()
    {
        $numeratorNutrient = $this->getNumeratorNutrient();
        $denominatorNutrient = $this->getDenominatorNutrient();
        if(!$denominatorNutrient || !$numeratorNutrient || $denominatorNutrient['NutrientAmount'] == 0 || $denominatorNutrient['NutrientAmount'] == 'NA') return [
            'value' => 'NA',
            'unit' => $numeratorNutrient['NutrientUnit'] . ' ' . $numeratorNutrient['NutrientSymbol'] .
                ' / ' . $denominatorNutrient['NutrientUnit'] . ' ' . $denominatorNutrient['NutrientSymbol']
        ];

        return [
            'value' => round($numeratorNutrient['NutrientAmount'] / $denominatorNutrient['NutrientAmount'], 1),
            'unit' => $numeratorNutrient['NutrientUnit'] . ' ' . $numeratorNutrient['NutrientSymbol'] .
                ' / ' . $denominatorNutrient['NutrientUnit'] . ' ' . $denominatorNutrient['NutrientSymbol']
        ];
    }

    private function getNumeratorNutrient()
    {
        $nutrientsDensityItems = collect(explode(',', env('NUTRIENT_DENSITY')));

        return collect($this->nutrients->filter(function($nutrient) use ($nutrientsDensityItems){
            return $nutrient['NutrientID'] ==  $nutrientsDensityItems[0];
        }))->first();
    }

    private function getDenominatorNutrient()
    {
        $nutrientsDensityItems = collect(explode(',', env('NUTRIENT_DENSITY')));

        return collect($this->nutrients->filter(function($nutrient) use ($nutrientsDensityItems){
            return $nutrient['NutrientID'] ==  $nutrientsDensityItems[1];
        }))->first();
    }

    public function scopeFoodnameSearch(Builder $query, ?string $searchText = null)
    {
        if (is_null($searchText)) {
            return $query;
        }

        $query->whereHas('foodname', function($query) use ($searchText) {
            $terms = collect(array_map('trim',explode(',', $searchText)));
            $terms->each(function($term) use($query){
                $query->where('FoodDescription', 'like', "%$term%" );
            });
        });
    }

    public function scopeFavouriteFilter(Builder $query, $favouritefilter)
    {
        if ($favouritefilter==='yes') {
            $query->join('favourites', 'favourites.ConversionFactorID', '=', 'conversionfactors.id');

//            $favouriteIds = User::find(auth()
//                ->user()->id)
//                ->favourites()->pluck('ConversionFactorID');
//            $query->whereIn('id', $favouriteIds);;
        }
    }

    public function scopeUserOwnedOrShared(Builder $query)
    {
        $query->where('user_id', null)
            ->orWhere('user_id', auth()->user()->id);
    }
}
