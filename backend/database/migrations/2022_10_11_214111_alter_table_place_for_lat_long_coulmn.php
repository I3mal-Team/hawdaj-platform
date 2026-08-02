<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTablePlaceForLatLongCoulmn extends Migration
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
            $table->dropColumn('lat');
            $table->dropColumn('long');
        });

        if (Schema::hasTable('places')) {
        Schema::table('places', function (Blueprint $table) {
            if (!Schema::hasColumn('places', 'lat')) {
            $table->string('lat')->nullable();
            }
            if (!Schema::hasColumn('places', 'long')) {
            $table->string('long')->nullable();
            }
        });

        if (Schema::hasTable('opinions')) {
        Schema::table('opinions', function (Blueprint $table) {
            $table->dropColumn('email');
        });

        if (Schema::hasTable('opinions')) {
        Schema::table('opinions', function (Blueprint $table) {
            if (!Schema::hasColumn('places', 'email')) {
            $table->string('email')->nullable();
            }
        });
    }
        }
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
        });
    }
}
