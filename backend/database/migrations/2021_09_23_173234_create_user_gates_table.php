<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserGatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('user_gates')) {
        Schema::create('user_gates', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('gate_id')->constrained('gates');
            $table->index('user_id');
            $table->primary(['user_id','gate_id']);
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
        Schema::dropIfExists('user_gates');
    }
}
