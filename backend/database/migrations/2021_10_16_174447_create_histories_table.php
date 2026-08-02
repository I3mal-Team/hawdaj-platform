<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('histories')) {
        Schema::create('histories', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->time('time')->nullable();
            $table->string('activity_type')->nullable();
            $table->text('comment')->nullable();
            $table->morphs('historiable');
            $table->timestamps();
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
        Schema::dropIfExists('histories');
    }
}
