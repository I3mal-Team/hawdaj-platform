<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTripsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('trips')) {
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('item_per_day')->nullable();
            $table->string('days')->nullable();
            $table->text('items')->nullable();
            $table->date('date')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();
        });
    }
        }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trips');
    }
}
