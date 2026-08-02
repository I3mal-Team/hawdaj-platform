<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryZadElgadelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('category_zad_elgadels')) {
        Schema::create('category_zad_elgadels', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('category_of_zads')->nullOnDelete();
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
        Schema::dropIfExists('category_zad_elgadels');
    }
}
