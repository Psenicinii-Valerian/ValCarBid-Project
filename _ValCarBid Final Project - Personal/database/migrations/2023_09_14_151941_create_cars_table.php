<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarsTable extends Migration
{
    public function up()
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('make', 50);
            $table->string('model', 70);
            $table->integer('year');
            $table->string('type', 5);
            $table->string('body', 20);
            $table->decimal('mileage', 9, 2);
            $table->string('vin', 17);
            $table->integer('cylinders')->nullable();
            $table->integer('engine_power');
            $table->decimal('displacement', 6, 1)->nullable();
            $table->decimal('battery_capacity', 6, 1)->nullable();
            $table->string('transmission_type', 50);
            $table->string('drive_type', 4);
            $table->string('fuel_type', 50);
            $table->integer('door_count');
            $table->integer('capacity');
            $table->boolean('crashes');
            $table->string('crash_description', 255)->nullable();
            $table->unsignedBigInteger('seller_id');
            $table->softDeletes(); 
            $table->timestamps();

            // Foreign Keys
            $table->foreign('seller_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('cars');
    }
}
