<?php

use Illuminate\Support\Facades\Route;

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

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', function(){
        return redirect(route('logentries.index'));
    });

//    Route::get('/conversionfactors', App\Http\Controllers\Conversionfactor\IndexController::class)->name('conversionfactors.index');
//    Route::get('/conversionfactors/create', App\Http\Controllers\Conversionfactor\CreateController::class)->name('conversionfactors.create');
//    Route::post('/conversionfactors', App\Http\Controllers\Conversionfactor\StoreController::class)->name('conversionfactors.store');
//    Route::post('/conversionfactors/{conversionfactor}/toggle-favourite', App\Http\Controllers\Conversionfactor\ToggleFavouriteController::class)->name('conversionfactors.toggle-favourite');
//    Route::delete('/conversionfactors/destroy/{conversionfactor}', App\Http\Controllers\Conversionfactor\DestroyController::class)->name('conversionfactors.destroy');
//    Route::patch('/conversionfactors/update/{conversionfactor}', App\Http\Controllers\Conversionfactor\UpdateController::class)->name('conversionfactors.update');
//    Route::get('/conversionfactors/edit/{conversionfactor}', App\Http\Controllers\Conversionfactor\EditController::class)->name('conversionfactors.edit');

    Route::get('/logentries', App\Http\Controllers\Logentry\IndexController::class)->name('logentries.index');
    Route::post('/logentries/store', App\Http\Controllers\Logentry\StoreController::class)->name('logentries.store');
    Route::patch('/logentries/update/{logentry}', App\Http\Controllers\Logentry\UpdateController::class)->name('logentries.update');
    Route::delete('/logentries/destroy/{logentry}', App\Http\Controllers\Logentry\DestroyController::class)->name('logentries.destroy');

    Route::get('/foods', App\Http\Controllers\Newfood\IndexController::class)->name('foods.index');
    Route::get('/foods/create', App\Http\Controllers\Newfood\CreateController::class)->name('foods.create');
    Route::post('/foods', App\Http\Controllers\Newfood\StoreController::class)->name('foods.store');
    Route::delete('/foods/destroy/{food}', App\Http\Controllers\Newfood\DestroyController::class)->name('foods.destroy');
    Route::patch('/foods/update/{food}', App\Http\Controllers\Newfood\UpdateController::class)->name('foods.update');
    Route::get('/foods/edit/{food}', App\Http\Controllers\Newfood\EditController::class)->name('foods.edit');
    Route::post('/foods/{food}/toggle-favourite', App\Http\Controllers\Newfood\ToggleFavouriteController::class)->name('foods.toggle-favourite');
});


require __DIR__.'/auth.php';
