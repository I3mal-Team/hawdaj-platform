<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStartDataAndEndDataToTripsTable extends Migration
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
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->unsignedBigInteger('region1')->nullable();
            $table->foreign('region1')->references('id')->on('regions')->onDelete('cascade');
            $table->unsignedBigInteger('region2')->nullable();
            $table->foreign('region2')->references('id')->on('regions')->onDelete('cascade');
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
            $table->dropColumn('start_date');
            $table->dropColumn('end_date');
            $table->dropForeign(['region1']);
            $table->dropColumn('region1');
            $table->dropForeign(['region2']);
            $table->dropColumn('region2');
        });
    }
}
