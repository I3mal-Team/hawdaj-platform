<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMenuFileToZadElgadels extends Migration
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
            $table->string('menu_file')->nullable();
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
            $table->dropColumn('image');
        });
    }
}
