<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('users')) {
        Schema::table('users', function (Blueprint $table) {
            $table->string('provider_id')->nullable();
            $table->string('provider_type')->nullable();
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
        if (Schema::hasTable('users')) {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('provider_id');
            $table->dropColumn('provider_type');
        });
    }
};
