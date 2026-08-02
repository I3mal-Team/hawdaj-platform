<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddViewsNumCoulmToStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('stores')) {
        Schema::table('stores', function (Blueprint $table) {
            $table->bigInteger('views_num')->default(0);
            $table->text('city_id')->nullable();
            $table->text('region_id')->nullable();
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
        if (Schema::hasTable('stores')) {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn('views_num');
            $table->dropColumn('city_id');
            $table->dropColumn('region_id');
        });
    }
}
