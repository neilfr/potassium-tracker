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
    Route::get('/conversionfactors/create', App\Http\Controllers\Conversionfactor\CreateController::class)->name('conversionfactors.create');
    Route::post('/conversionfactors', App\Http\Controllers\Conversionfactor\StoreController::class)->name('conversionfactors.store');
    Route::post('/conversionfactors/{conversionfactor}/toggle-favourite', App\Http\Controllers\Conversionfactor\ToggleFavouriteController::class)->name('conversionfactors.toggle-favourite');
    Route::delete('/conversionfactors/destroy/{conversionfactor}', App\Http\Controllers\Conversionfactor\DestroyController::class)->name('conversionfactors.destroy');
    Route::patch('/conversionfactors/update/{conversionfactor}', App\Http\Controllers\Conversionfactor\UpdateController::class)->name('conversionfactors.update');
    Route::get('/conversionfactors/edit/{conversionfactor}', App\Http\Controllers\Conversionfactor\EditController::class)->name('conversionfactors.edit');
    Route::get('/logentries', App\Http\Controllers\Logentry\IndexController::class)->name('logentries.index');
    Route::post('/logentries/store', App\Http\Controllers\Logentry\StoreController::class)->name('logentries.store');
    Route::patch('/logentries/update/{logentry}', App\Http\Controllers\Logentry\UpdateController::class)->name('logentries.update');
    Route::delete('/logentries/destroy/{logentry}', App\Http\Controllers\Logentry\DestroyController::class)->name('logentries.destroy');
});


require __DIR__.'/auth.php';
