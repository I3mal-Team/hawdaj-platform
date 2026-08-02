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
        if (!Schema::hasTable('place_translations')) {
        Schema::create('place_translations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('place_id')->unsigned();
            $table->string('locale')->index();
            $table->string('title');
            $table->text('description');
            $table->unique(['place_id', 'locale']);
            $table->foreign('place_id')->references('id')->on('places')->onDelete('cascade');
        });

        foreach (\App\Models\Place::get() as $place) {
            $title = $place->title ?? '-';
            $description = $place->description ?? '-';

            $place->translateOrNew('ar')->title = $title;
            $place->translateOrNew('ar')->description = $description;

            $title = GoogleTranslate::trans($title, "en");
            $description = GoogleTranslate::trans($description, "en");

            $place->translateOrNew('en')->title = $title;
            $place->translateOrNew('en')->description = $description;
            $place->slug = \Illuminate\Support\Str::slug($title);
            $place->save();
        }

        Schema::table('places', function (Blueprint $table) {
            $table->dropColumn('title');
            $table->dropColumn('description');
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
        Schema::dropIfExists('place_translations');
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
};
