<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdToRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('rates')) {
        Schema::table('rates', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable();
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
        if (Schema::hasTable('rates')) {
        Schema::table('rates', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });
    }
}
