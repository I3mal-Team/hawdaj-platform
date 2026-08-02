<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIosAndroidToApplicationsTable extends Migration
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
            $table->text('ios_link')->nullable();
            $table->text('android_link')->nullable();
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
            $table->dropColumn(['ios_link', 'android_link']);
        });
    }
}
