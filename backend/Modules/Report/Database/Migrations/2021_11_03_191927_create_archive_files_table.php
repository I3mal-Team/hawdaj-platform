<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArchiveFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('archive_files', function (Blueprint $table) {
            $table->id();
            $table->string('start');
            $table->string('end');
            $table->foreignId('site_id')->constrained('sites');
            $table->string('type');
            $table->string('model_type');
            $table->string('url',250)->nullable();
            $table->string('size',250)->nullable();
            $table->string('name')->nullable();
            $table->tinyInteger('status')->default(false);
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('archive_files');
    }
}
