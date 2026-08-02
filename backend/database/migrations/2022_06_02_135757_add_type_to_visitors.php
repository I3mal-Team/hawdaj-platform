<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeToVisitors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('visitors')) {
        Schema::table('visitors', function (Blueprint $table) {
            $table->enum('type', ['visitor', 'contractor'])->default('visitor');
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
        if (Schema::hasTable('visitors')) {
        Schema::table('visitors', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}
