<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpiredListingsTable extends Migration
{
    public function up()
    {
        Schema::create('expired_listings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('expired_listing_id')->unique();
            $table->unsignedBigInteger('expired_car_id');
            $table->integer('bid_price');
            $table->integer('buy_price');
            $table->unsignedBigInteger('current_winner_id')->nullable();
            $table->timestamp('expires_at')->default(now());
            $table->softDeletes(); 
            $table->timestamps();

            // Foreign keys
            $table->foreign('expired_car_id')->references('id')->on('cars')->onDelete('cascade');;
            $table->foreign('current_winner_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('expired_listings');
    }
}
