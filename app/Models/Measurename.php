<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Measurename extends Model
{
    use HasFactory;

    protected $primaryKey = 'MeasureID';

    protected $fillable = [
        'MeasureDescription',
    ];

    public function foodnames(){
        return $this->belongsToMany(Foodname::class, 'conversionfactors', 'MeasureID', 'FoodID')
            ->withPivot('ConversionFactorValue');
    }
}
