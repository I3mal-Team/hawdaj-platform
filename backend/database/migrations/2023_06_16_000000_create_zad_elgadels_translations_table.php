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
        if (!Schema::hasTable('zad_elgadel_translations')) {
        Schema::create('zad_elgadel_translations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('zad_elgadel_id')->unsigned();
            $table->string('locale')->index();
            $table->string('title');
            $table->text('description');
            $table->unique(['zad_elgadel_id', 'locale']);
            $table->foreign('zad_elgadel_id')->references('id')->on('zad_elgadels')->onDelete('cascade');
        });

        foreach (\App\Models\ZadElgadel::get() as $zad_elgadel) {
            $title = $zad_elgadel->title ?? '-';
            $description = $zad_elgadel->description ?? '-';

            $zad_elgadel->translateOrNew('en')->title = $title;
            $zad_elgadel->translateOrNew('en')->description = $description;

            $title = GoogleTranslate::trans($title, "ar");
            $description = GoogleTranslate::trans($description, "ar");

            $zad_elgadel->translateOrNew('ar')->title = $title;
            $zad_elgadel->translateOrNew('ar')->description = $description;

            $zad_elgadel->slug = \Illuminate\Support\Str::slug($title);
            $zad_elgadel->save();
        }

        Schema::table('zad_elgadels', function (Blueprint $table) {
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
        Schema::dropIfExists('zad_elgadel_translations');
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
};
