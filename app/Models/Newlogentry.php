<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Newlogentry extends Model
{
    use HasFactory;

    protected $fillable = ['NewfoodID', 'UserID', 'ConsumedAt', 'portion'];

    public function newfood()
    {
        return $this->belongsto(Newfood::class, 'NewfoodID', 'NewfoodID');
    }

    public function scopeInDateRange(Builder $query, $from, $to)
    {
        $now = now();

        if (is_null($from) && is_null($to)){
            return $query->where('ConsumedAt', '>=', $now->toDateString())
                ->where('ConsumedAt', '<=', $now->toDateString());
        }

        if (is_null($from)){
            return $query->whereDate('ConsumedAt', '<=', Carbon::parse($to)->toDateString());
        }

        if (is_null($to)){
            return $query->whereDate('ConsumedAt', '>=', Carbon::parse($from)->toDateString());
        }

        $query->whereBetween('ConsumedAt', [Carbon::parse($from)->toDateString(), Carbon::parse($to)->toDateString()]);
    }
}
