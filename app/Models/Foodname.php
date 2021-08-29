<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Foodname extends Model
{
    use HasFactory;

    protected $primaryKey = 'FoodID';

    public function foodgroup(){
        return $this->belongsTo(Foodgroup::class, 'FoodGroupID', 'FoodGroupID');
    }

}
