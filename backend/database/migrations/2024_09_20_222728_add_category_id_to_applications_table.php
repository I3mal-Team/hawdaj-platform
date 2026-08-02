<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCategoryIdToApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('applications')) {
        Schema::table('applications', function (Blueprint $table) {
            $table->text('categories')->nullable();
            $table->tinyInteger('show_in_home')->nullable()->default(0);
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
        if (Schema::hasTable('applications')) {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn('categories');
            $table->dropColumn('show_in_home');
        });
    }
}
