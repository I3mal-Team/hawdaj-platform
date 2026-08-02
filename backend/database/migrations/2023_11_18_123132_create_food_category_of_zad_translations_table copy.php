<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Stichoza\GoogleTranslate\GoogleTranslate;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('food_category_of_zad_translations')) {
        Schema::create('food_category_of_zad_translations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('food_category_of_zad_id')->unsigned();
            $table->string('locale')->index();
            $table->string('name');
            $table->text('notes')->nullable();
            $table->unique(['food_category_of_zad_id', 'locale'], 'food_category_of_zad_unique');
            $table->foreign('food_category_of_zad_id' , 'food_category_of_zad_id_foreign')->references('id')->on('food_category_of_zads')->onDelete('cascade');
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
        \DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('food_category_of_zad_translations');
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
};
