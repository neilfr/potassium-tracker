<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Conversionfactor extends Pivot
{
    use HasFactory;

    protected $table='conversionfactors';

    public function foodname(){
        return $this->belongsTo(Foodname::class,'FoodID');
    }

    public function measurename(){
        return $this->belongsTo(Measurename::class,'MeasureID');
    }

}
