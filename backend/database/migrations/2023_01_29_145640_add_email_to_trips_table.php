<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmailToTripsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('trips')) {
        Schema::table('trips', function (Blueprint $table) {
            $table->string('email')->nullable();
            $table->text('token')->nullable();
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
        if (Schema::hasTable('trips')) {
        Schema::table('trips', function (Blueprint $table) {
            $table->dropColumn('email');
            $table->dropColumn('token');
        });
    }
}
