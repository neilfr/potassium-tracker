<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConversionfactorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conversionfactors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('FoodID');
            $table->unsignedBigInteger('MeasureID');
            $table->float('ConversionFactorValue', 8, 5);
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
        Schema::dropIfExists('conversionfactors');
    }
}
