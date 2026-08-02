<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKeyWordsToMailTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('mail_templates')) {
        Schema::table('mail_templates', function (Blueprint $table) {
            $table->longText('key_words')->nullable();
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
        if (Schema::hasTable('mail_templates')) {
        Schema::table('mail_templates', function (Blueprint $table) {
            //
        });
    }
}
