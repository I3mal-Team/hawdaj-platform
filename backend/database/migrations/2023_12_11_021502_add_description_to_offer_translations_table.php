<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDescriptionToOfferTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('offer_translations')) {
        Schema::table('offer_translations', function (Blueprint $table) {
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
        if (Schema::hasTable('offer_translations')) {
        Schema::table('offer_translations', function (Blueprint $table) {
            $table->dropColumn('description');
        });
    }
}
