<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeAttrToStoreTable extends Migration
{

    public function up()
    {
        if (Schema::hasTable('stores')) {
        Schema::table('stores', function (Blueprint $table) {
            if (Schema::hasColumn('stores','region_id'))
                $table->foreignId('region_id')->nullable()->change()->constrained();
            else
                $table->foreignId('region_id')->nullable()->constrained();

            if (Schema::hasColumn('stores','city_id'))
                $table->foreignId('city_id')->nullable()->change()->constrained();
            else
                $table->foreignId('city_id')->nullable()->constrained();
        });
    }
        }

    public function down()
    {
        if (Schema::hasTable('stores')) {
        Schema::table('stores', function (Blueprint $table) {
            //
        });
    }
}
