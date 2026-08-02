<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropLatLongInStoresTable extends Migration
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
            $table->dropColumn('lat');
            $table->dropColumn('long');
        });

        if (Schema::hasTable('stores')) {
        Schema::table('stores', function (Blueprint $table) {
            if (!Schema::hasColumn('stores', 'lat')) {
            $table->string('lat')->nullable();
            }
            if (!Schema::hasColumn('stores', 'long')) {
            $table->string('long')->nullable();
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
        if (Schema::hasTable('stores')) {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn('lat');
            $table->dropColumn('long');
        });
    }
}
