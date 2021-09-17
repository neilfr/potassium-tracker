<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logentry extends Model
{
    use HasFactory;

//    public function Foodnames()
//    {
//        return $this->belongsTo(Foodgroup::class);
//    }

    public function conversionfactor()
    {
        return $this->belongsTo(Conversionfactor::class, 'ConversionFactorID', 'id');
    }
}
