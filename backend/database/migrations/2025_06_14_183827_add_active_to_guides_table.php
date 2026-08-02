<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddActiveToGuidesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('guides')) {
        Schema::table('guides', function (Blueprint $table) {
            $table->tinyInteger('active')->default(0)->nullable();
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
        if (Schema::hasTable('guides')) {
        Schema::table('guides', function (Blueprint $table) {
            $table->dropColumn('active');
        });
    }
}
