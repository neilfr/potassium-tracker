<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFoodFavouritesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('food_favourites', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('UserID');
            $table->unsignedBigInteger('FoodID');
            $table->timestamps();
            $table->foreign('UserID')->references('id')->on('users');
            $table->foreign('FoodID')->references('id')->on('foods');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('food_favourites');
    }
}
