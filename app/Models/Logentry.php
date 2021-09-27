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
        $now = now()->toDateString();
        if (is_null($from) && is_null($to)){
            return $query->where('ConsumedAt', '>=', $now)
                ->where('ConsumedAt', '<=', $now);
        }

        if (is_null($from)){
            return $query->whereDate('ConsumedAt', '<=', Carbon::now($to)->addDay()->toDateString());
        }

        if (is_null($to)){
            return $query->whereDate('ConsumedAt', '>=', Carbon::now($from)->toDateString());
        }

        $query->whereBetween('ConsumedAt', [$from, $to]);
    }
}
