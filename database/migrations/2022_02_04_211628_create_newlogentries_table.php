<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewlogentriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('newlogentries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('UserID');
            $table->unsignedBigInteger('NewfoodID');
            $table->dateTime('ConsumedAt');
            $table->integer('portion');

            $table->timestamps();

            $table->foreign('UserID','newlogentry_user_foreignkey')->references('id')->on('users');
            $table->foreign('NewfoodID','newlogentry_newfood_foreignkey')->references('NewfoodID')->on('newfoods');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('newlogentries');
    }
}
