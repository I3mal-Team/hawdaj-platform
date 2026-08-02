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
        if (!Schema::hasTable('category_translations')) {
        Schema::create('category_translations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('category_id')->unsigned();
            $table->string('locale')->index();
            $table->string('name');
            $table->text('notes')->nullable();
            $table->unique(['category_id', 'locale']);
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });

        foreach (\App\Models\Category::get() as $category) {
            $name = $category->name ?? '-';
            $notes = $category_of_store->notes ?? '';
            $category->translateOrNew('ar')->name = $name;
            $category->translateOrNew('ar')->notes = $notes;

            $name = GoogleTranslate::trans($name, "en");
            $notes = GoogleTranslate::trans($notes, "en");

            $category->translateOrNew('en')->name = $name;
            $category->translateOrNew('en')->notes = $notes;
            $category->save();
        }

        Schema::table('categories', function (Blueprint $table) {
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
        Schema::dropIfExists('category_translations');
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
};
