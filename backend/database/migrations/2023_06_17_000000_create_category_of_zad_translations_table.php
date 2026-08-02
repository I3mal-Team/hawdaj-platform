<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Stichoza\GoogleTranslate\GoogleTranslate;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('category_of_zad_translations')) {
        Schema::create('category_of_zad_translations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('category_of_zad_id')->unsigned();
            $table->string('locale')->index();
            $table->string('name');
            $table->text('notes')->nullable();
            $table->unique(['category_of_zad_id', 'locale'], 'category_of_zad_unique');
            $table->foreign('category_of_zad_id')->references('id')->on('category_of_zads')->onDelete('cascade');
        });

        foreach (\App\Models\CategoryOfStore::get() as $category_of_zad) {
            $name = $category_of_zad->name ?? '-';
            $notes = $category_of_store->notes ?? '';
            $category_of_zad->translateOrNew('ar')->name = $name;
            $category_of_zad->translateOrNew('ar')->notes = $notes;

            $name = GoogleTranslate::trans($name, "en");
            $notes = GoogleTranslate::trans($notes, "en");

            $category_of_zad->translateOrNew('en')->name = $name;
            $category_of_zad->translateOrNew('en')->notes = $notes;
            $category_of_zad->save();
        }

        Schema::table('category_of_zads', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('notes');
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
        \DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('category_of_zad_translations');
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
};
