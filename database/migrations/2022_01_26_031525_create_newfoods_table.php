<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewfoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('newfoods', function (Blueprint $table) {
            $table->id('NewfoodID');
            $table->unsignedBigInteger('ConversionFactorID')->nullable();
            $table->unsignedBigInteger('UserID')->nullable();
            $table->unsignedBigInteger('FoodGroupID');
            $table->unsignedBigInteger('FoodID')->nullable();
            $table->unsignedBigInteger('MeasureID')->nullable();
            $table->string('FoodGroupName');
            $table->string('FoodDescription');
            $table->string('MeasureDescription');
            $table->decimal('KCalValue',10,5)->nullable();
            $table->decimal('PotassiumValue',10,5)->nullable();
            $table->decimal('NutrientDensity')->nullable();
            $table->timestamps();

            $table->foreign('ConversionFactorID')->references('id')->on('conversionfactors');
            $table->foreign('UserID')->references('id')->on('users');
            $table->foreign('FoodID')->references('FoodID')->on('foodnames');
            $table->foreign('MeasureID')->references('MeasureID')->on('measurenames');
            $table->index('FoodDescription');
            $table->index('NutrientDensity');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('foods');
    }
}
