<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nutrientname extends Model
{
    use HasFactory;

    protected $primaryKey = 'NutrientID';

    public function foodnames(){
        return $this->belongsToMany(Foodname::class, 'nutrientamounts', 'NutrientID', 'FoodID')
            ->withPivot('NutrientValue');
    }
}
