<?php

namespace App\Http\Controllers\Foodname;

use App\Http\Controllers\Controller;
use App\Models\Foodname;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        return Foodname::all();
    }
}
