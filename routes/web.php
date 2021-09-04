<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/foodgroups', App\Http\Controllers\FoodGroup\IndexController::class)->name('foodgroups.index');
    Route::get('/foodnames', App\Http\Controllers\Foodname\IndexController::class)->name('foodnames.index');
    Route::get('/foodname-nutrients', App\Http\Controllers\FoodnameNutrients\IndexController::class)->name('foodname-nutrients.index');
    Route::get('/conversionfactors', App\Http\Controllers\Conversionfactor\IndexController::class)->name('conversionfactors.index');
});


require __DIR__.'/auth.php';
