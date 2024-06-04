<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateListingsTable extends Migration
{
    public function up()
    {
        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('car_id');
            $table->integer('bid_price');
            $table->integer('buy_price');
            $table->unsignedBigInteger('current_winner_id')->nullable();
            $table->timestamp('expires_at')->default(now());
            $table->softDeletes();
            $table->timestamps();

            // Foreign Key
            $table->foreign('car_id')->references('id')->on('cars')->onDelete('cascade');
            $table->foreign('current_winner_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('listings');
    }
}

