<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Foodname extends Model
{
    use HasFactory;

    protected $primaryKey = 'FoodID';

    protected $fillable = [
        'FoodDescription',
    ];

    protected $with = ['nutrientnames'];

    public function foodgroup(){
        return $this->belongsTo(Foodgroup::class, 'FoodGroupID', 'FoodGroupID');
    }

    public function nutrientnames(){
        return $this->belongsToMany(Nutrientname::class, 'nutrientamounts', 'FoodID', 'NutrientID')
            ->withPivot('NutrientValue');
    }

    public function measurenames(){
        return $this->belongsToMany(Measurename::class, 'conversionfactors', 'FoodID', 'MeasureID')
            ->withPivot(['ConversionFactorValue','id']);
    }
}
