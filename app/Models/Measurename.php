<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Measurename extends Model
{
    use HasFactory;

    protected $primaryKey = 'MeasureID';

    public function foodnames(){
        return $this->belongsToMany(Foodname::class, 'conversionfactors', 'FoodID', 'MeasureID')
            ->withPivot('ConversionFactorValue');
    }
}
