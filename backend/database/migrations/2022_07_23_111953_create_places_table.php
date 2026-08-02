<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('places')) {
        Schema::create('places', function (Blueprint $table) {
            $table->id();
            $table->text('title')->nullable();
            $table->text('categories')->nullable();
            $table->text('address')->nullable();
            $table->text('description')->nullable();
            $table->text('image')->nullable();
            $table->text('city_id')->nullable();
            $table->text('region_id')->nullable();
            $table->unsignedInteger('temperature')->nullable();
            $table->text('seasons')->nullable();
            $table->text('price_id')->nullable();
            $table->string('lat')->nullable();
            $table->string('long')->nullable();
            $table->enum("address_type", ['link', 'map', 'latlong'])->default('link');
            $table->tinyInteger('active')->default(0);
            $table->softDeletes();
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
        Schema::dropIfExists('places');
    }
}
