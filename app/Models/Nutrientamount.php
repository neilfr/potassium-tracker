<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Nutrientamount extends Pivot
{
    protected $primaryKey = true;

    protected $table='nutrientamounts';
}
