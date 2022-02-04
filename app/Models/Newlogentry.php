<?php

namespace App\Models;

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
}
