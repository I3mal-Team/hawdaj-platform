<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('user_sites')) {
        Schema::create('user_sites', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('site_id')->constrained('sites');
            $table->index('user_id');
            $table->primary(['user_id','site_id']);
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
        Schema::dropIfExists('user_sites');
    }
}
