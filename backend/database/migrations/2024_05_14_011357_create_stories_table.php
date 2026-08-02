<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('stories')) {
        Schema::create('stories', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable(); // text, file
            $table->string('file')->nullable();
            $table->text('text')->nullable();
            $table->string('status')->nullable();
            $table->integer('total_views')->nullable();
            $table->integer('total_likes')->nullable();
            $table->integer('total_comments')->nullable();
            $table->integer('total_shares')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
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
        Schema::dropIfExists('stories');
    }
}
