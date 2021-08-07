<?php

namespace App\Http\Controllers;

use App\Models\Foodgroup;
use Illuminate\Http\Request;

class FoodgroupController extends Controller
{
    public function index()
    {
        return Foodgroup::all()->toArray();
    }
}
