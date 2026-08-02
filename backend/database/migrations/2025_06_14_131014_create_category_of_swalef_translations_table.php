<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryOfSwalefTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('category_of_swalef_translations')) {
        Schema::create('category_of_swalef_translations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('category_of_swalef_id')->unsigned();
            $table->string('locale')->index();
            $table->string('name');
            $table->text('notes')->nullable();
            $table->unique(['category_of_swalef_id', 'locale'], 'category_of_swalef_unique');
            // Specify a shorter foreign key name
            $table->foreign('category_of_swalef_id', 'fk_category_of_swalefs_translations_foreign')
                ->references('id')->on('category_of_swalefs')
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
        Schema::dropIfExists('category_of_swalefs_translations');
    }
}
