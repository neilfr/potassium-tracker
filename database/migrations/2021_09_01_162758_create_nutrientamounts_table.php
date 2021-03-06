<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNutrientamountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nutrientamounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('FoodID');
            $table->unsignedBigInteger('NutrientID');
            $table->float('NutrientValue', 9, 4);
            $table->foreign('FoodID')->references('FoodID')->on('foodnames');
            $table->foreign('NutrientID')->references('NutrientID')->on('nutrientnames');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nutrientamounts');
    }
}
