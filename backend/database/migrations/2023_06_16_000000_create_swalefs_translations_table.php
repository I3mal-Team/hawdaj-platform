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
        if (!Schema::hasTable('swalef_translations')) {
        Schema::create('swalef_translations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('swalef_id')->unsigned();
            $table->string('locale')->index();
            $table->string('title');
            $table->text('description');
            $table->unique(['swalef_id', 'locale']);
            $table->foreign('swalef_id')->references('id')->on('swalefs')->onDelete('cascade');
        });

        foreach (\App\Models\Swalef::get() as $swalef) {
            $title = $swalef->title ?? '-';
            $description = $swalef->description ?? '-';

            $swalef->translateOrNew('en')->title = $title;
            $swalef->translateOrNew('en')->description = $description;

            $title = GoogleTranslate::trans($title, "en");
            $description = GoogleTranslate::trans($description, "en");

            $swalef->translateOrNew('ar')->title = $title;
            $swalef->translateOrNew('ar')->description = $description;

            $swalef->slug = \Illuminate\Support\Str::slug($title);
            $swalef->save();
        }

        Schema::table('swalefs', function (Blueprint $table) {
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
        Schema::dropIfExists('swalef_translations');
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
};
