<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddViewsCoulmnToPlacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('places')) {
        Schema::table('places', function (Blueprint $table) {
            $table->bigInteger('views_num')->default(0);
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
        if (Schema::hasTable('places')) {
        Schema::table('places', function (Blueprint $table) {
            $table->dropColumn('views_num');
        });
    }
}
