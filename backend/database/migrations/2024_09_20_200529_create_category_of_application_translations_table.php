<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryOfApplicationTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('category_of_application_translations')) {
        Schema::create('category_of_application_translations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('category_of_application_id')->unsigned();
            $table->string('locale')->index();
            $table->string('name');
            $table->text('notes')->nullable();
            $table->unique(['category_of_application_id', 'locale'], 'category_of_application_unique');
            // Specify a shorter foreign key name
            $table->foreign('category_of_application_id', 'fk_category_translation_app_id')
                ->references('id')->on('category_of_applications')
                ->onDelete('cascade');
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
        Schema::dropIfExists('category_of_application_translations');
    }
}
