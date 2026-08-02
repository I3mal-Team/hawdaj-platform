<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMailTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('mail_templates')) {
        Schema::create('mail_templates', function (Blueprint $table) {
            $table->id();
            $table->text('type')->nullable();
            $table->text('title')->nullable();
            $table->text('subject')->nullable();
            $table->longText('content')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('mail_templates');
    }
}
