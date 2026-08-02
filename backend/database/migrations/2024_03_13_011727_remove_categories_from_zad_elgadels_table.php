<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveCategoriesFromZadElgadelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('zad_elgadels')) {
        Schema::table('zad_elgadels', function (Blueprint $table) {
            $table->dropColumn('categories');
            $table->dropColumn('food_categories');
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
        if (Schema::hasTable('zad_elgadels')) {
        Schema::table('zad_elgadels', function (Blueprint $table) {
            if (!Schema::hasColumn('zad_elgadels', 'categories')) {
            $table->text('categories');
            }
            if (!Schema::hasColumn('zad_elgadels', 'food_categories')) {
            $table->text('food_categories');
            }
        });
    }
}
