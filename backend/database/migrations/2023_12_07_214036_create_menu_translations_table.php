<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('menu_translations')) {
        Schema::create('menu_translations', function (Blueprint $table) {
            $table->id();
            $table->text('description');
            $table->string('locale')->index();
            $table->foreignId('menu_id')->constrained('menus')->onDelete('cascade');
            $table->unique(['menu_id', 'locale']);
            $table->string('title');
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
        Schema::dropIfExists('menu_translations');
    }
}
