<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrepareTripsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('prepare_trips')) {
        Schema::create('prepare_trips', function (Blueprint $table) {
            $table->id();
            $table->text('items')->nullable();
            $table->text('places')->nullable();
            $table->text('selected_categories')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('funny')->nullable();
            $table->string('funny_place_per_day')->nullable();
            $table->string('days')->nullable();
            $table->string('lat1')->nullable();
            $table->string('long1')->nullable();
            $table->string('lat2')->nullable();
            $table->string('long2')->nullable();
            $table->string('token')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('region1')->nullable();
            $table->foreign('region1')->references('id')->on('regions')->onDelete('cascade');
            $table->unsignedBigInteger('region2')->nullable();
            $table->foreign('region2')->references('id')->on('regions')->onDelete('cascade');
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
        Schema::dropIfExists('prepare_trips');
    }
}
