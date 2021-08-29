<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFoodnamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('foodnames', function (Blueprint $table) {
            $table->id('FoodID');
            $table->unsignedBigInteger('FoodGroupID');
            $table->string('FoodCode')->nullable();
            $table->string('FoodDescription');
            $table->timestamps();
            $table->foreign('FoodGroupID')->references('FoodGroupID')->on('foodgroups');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('foodnames');
    }
}
