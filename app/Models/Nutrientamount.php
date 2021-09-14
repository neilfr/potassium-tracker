<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Nutrientamount extends Pivot
{
    use HasFactory;

//    protected $primaryKey = true;

    protected $table='nutrientamounts';
}
