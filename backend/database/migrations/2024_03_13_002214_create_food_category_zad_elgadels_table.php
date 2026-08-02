<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFoodCategoryZadElgadelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('food_category_zad_elgadels')) {
        Schema::create('food_category_zad_elgadels', function (Blueprint $table) {
            $table->unsignedBigInteger('food_category_id')->nullable();
            $table->foreign('food_category_id')->references('id')->on('food_category_of_zads')->nullOnDelete();
            $table->unsignedBigInteger('zad_elgadel_id')->nullable();
            $table->foreign('zad_elgadel_id')->references('id')->on('zad_elgadels')->nullOnDelete();
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
        Schema::dropIfExists('food_category_zad_elgadels');
    }
}
