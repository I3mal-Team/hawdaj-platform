<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationTranslationsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('application_translations')) {
        Schema::create('application_translations', function (Blueprint $table) {
            $table->id();
            $table->string('locale')->index();
            $table->foreignId('application_id')->constrained('applications')->onDelete('cascade');
            $table->unique(['application_id', 'locale']);
            $table->string('title')->nullable();
            $table->string('description')->nullable();
        });
    }
        }

    public function down()
    {
        Schema::dropIfExists('application_translations');
    }
}
