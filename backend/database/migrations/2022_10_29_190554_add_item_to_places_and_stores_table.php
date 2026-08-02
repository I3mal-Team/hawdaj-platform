<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddItemToPlacesAndStoresTable extends Migration
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
            $table->string('distance')->nullable();
        });
        if (Schema::hasTable('stores')) {
        Schema::table('stores', function (Blueprint $table) {
            if (!Schema::hasColumn('places', 'distance')) {
            $table->string('distance')->nullable();
            }
        });
    }
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
            $table->dropColumn('distance');
        });
        if (Schema::hasTable('stores')) {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn('distance');
        });
    }
}
        }
