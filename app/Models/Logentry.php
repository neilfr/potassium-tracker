<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logentry extends Model
{
    use HasFactory;

    protected $with = ['conversionfactor'];

    public function conversionfactor()
    {
        return $this->belongsTo(Conversionfactor::class, 'ConversionFactorID', 'id');
    }

    public function scopeInDateRange(Builder $query, $from, $to)
    {
        $now = now();

        if (is_null($from) && is_null($to)){
            return $query->where('ConsumedAt', '>=', $now->toDateString())
                ->where('ConsumedAt', '<=', $now->addDay()->toDateString());
        }

        if (is_null($from)){
            return $query->whereDate('ConsumedAt', '<=', Carbon::parse($to)->addDay()->toDateString());
        }

        if (is_null($to)){
            return $query->whereDate('ConsumedAt', '>=', Carbon::parse($from)->toDateString());
        }

        $query->whereBetween('ConsumedAt', [Carbon::parse($from)->toDateString(), Carbon::parse($to)->addDay()->toDateString()]);
    }
}
