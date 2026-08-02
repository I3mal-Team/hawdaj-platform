<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuideTranslationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('guide_translation')) {
        Schema::create('guide_translation', function (Blueprint $table) {
            $table->id();
            $table->string('locale')->index();
            $table->foreignId('guide_id')->constrained('guides')->onDelete('cascade');
            $table->unique(['guide_id', 'locale']);
            $table->string('name')->nullable();
            $table->string('nickName')->nullable();
            $table->text('description')->nullable();
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
        Schema::dropIfExists('guide_translation');
    }
}
