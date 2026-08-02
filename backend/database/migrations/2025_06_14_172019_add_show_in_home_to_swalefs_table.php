<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShowInHomeToSwalefsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('swalefs')) {
        Schema::table('swalefs', function (Blueprint $table) {
            $table->tinyInteger('show_in_home')->default(0)->nullable();
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
        if (Schema::hasTable('swalefs')) {
        Schema::table('swalefs', function (Blueprint $table) {
            $table->dropColumn('show_in_home');
        });
    }
}
