<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPlaceIconToPlacesTable extends Migration
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
            $table->text('place_icon')->nullable();
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
            $table->dropColumn('place_icon');
        });
    }
}
