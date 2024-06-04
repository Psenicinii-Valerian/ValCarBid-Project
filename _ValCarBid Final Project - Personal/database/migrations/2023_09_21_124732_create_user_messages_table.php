<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('car_id');
            $table->unsignedBigInteger('listing_id');
            $table->unsignedBigInteger('winner_id')->nullable();
            $table->unsignedBigInteger('seller_id');
            $table->string('status');
            $table->timestamp('winner_seen_at')->nullable()->default(null);
            $table->timestamp('seller_seen_at')->nullable()->default(null);
            $table->timestamps();
            $table->softDeletes(); 

            // Foreign Keys
            $table->foreign('car_id')->references('id')->on('cars')->onDelete('cascade');
            $table->foreign('listing_id')->references('id')->on('listings')->onDelete('cascade');
            $table->foreign('winner_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('seller_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_messages');
    }
};
