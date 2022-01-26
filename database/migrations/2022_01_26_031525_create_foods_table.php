<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //TODO: add foodgroup stuff
        Schema::create('foods', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ConversionFactorID');
            $table->unsignedBigInteger('UserID')->nullable();
            $table->unsignedBigInteger('FoodGroupID')->nullable();
            $table->unsignedBigInteger('FoodID');
            $table->unsignedBigInteger('MeasureID');
            $table->boolean('Favourite');
            $table->string('FoodGroupName');
            $table->string('FoodDescription');
            $table->string('MeasureDescription');
            $table->decimal('ConversionFactorValue',10,5);
            $table->decimal('KCalValue',10,5)->nullable();
            $table->string('KCalSymbol')->nullable();
            $table->string('KCalName')->nullable();
            $table->string('KCalUnit')->nullable();
            $table->decimal('PotassiumValue',10,5)->nullable();
            $table->string('PotassiumSymbol')->nullable();
            $table->string('PotassiumName')->nullable();
            $table->string('PotassiumUnit')->nullable();
            $table->string('NutrientDensity')->nullable();
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
