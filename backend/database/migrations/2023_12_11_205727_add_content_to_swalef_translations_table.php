<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddContentToSwalefTranslationsTable extends Migration
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
            $table->text('content')->nullable();
        });

        $swalefs = \App\Models\Swalef::all();
        foreach ($swalefs as $swalef) {
            $swalef->translateOrNew('ar')->content = $swalef->content;
            $swalef->save();
        }

        if (Schema::hasTable('swalefs')) {
        Schema::table('swalefs', function (Blueprint $table) {
            $table->dropColumn('content');
        });
    }
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
            $table->dropColumn('content');
        });

        if (Schema::hasTable('swalefs')) {
        Schema::table('swalefs', function (Blueprint $table) {
            if (!Schema::hasColumn('swalef_translations', 'content')) {
            $table->text('content')->nullable();
            }
        });
    }
}
        }
