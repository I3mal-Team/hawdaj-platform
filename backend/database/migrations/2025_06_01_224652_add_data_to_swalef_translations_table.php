<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDataToSwalefTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('swalef_translations')) {
        Schema::table('swalef_translations', function (Blueprint $table) {
            $table->text('timePeriod')->nullable();
            $table->text('location')->nullable();
            $table->text('storyImportance')->nullable();
            $table->text('relevanceToPresent')->nullable();
            $table->text('source')->nullable();
            $table->text('audioStoryTitle')->nullable();
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
        if (Schema::hasTable('swalef_translations')) {
        Schema::table('swalef_translations', function (Blueprint $table) {
            $table->dropColumn('timePeriod');
            $table->dropColumn('location');
            $table->dropColumn('storyImportance');
            $table->dropColumn('relevanceToPresent');
            $table->dropColumn('source');
            $table->dropColumn('audioStoryTitle');
        });
    }
}
