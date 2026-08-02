<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLatitudeAndLongitudeToMostVisitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('most_visits')) {
        Schema::table('most_visits', function (Blueprint $table) {
            $table->decimal('latitude', 10, 8)->nullable()->after('continent_code');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
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
        if (Schema::hasTable('most_visits')) {
        Schema::table('most_visits', function (Blueprint $table) {
            if (!Schema::hasColumn('most_visits', 'latitude')) {
            $table->decimal('latitude', 10, 8)->nullable()->after('continent_code');
            }
            if (!Schema::hasColumn('most_visits', 'longitude')) {
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            }
        });
    }
}
