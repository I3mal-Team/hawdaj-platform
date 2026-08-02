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
        if (!Schema::hasTable('event_translations')) {
        Schema::create('event_translations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('event_id')->unsigned();
            $table->string('locale')->index();
            $table->string('title');
            $table->text('description');
            $table->unique(['event_id', 'locale']);
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
        });

        foreach (\App\Models\Event::get() as $event) {
            $title = $event->title ?? '-';
            $description = $event->description ?? '-';

            $event->translateOrNew('ar')->title = $title;
            $event->translateOrNew('ar')->description = $description;

            $title = GoogleTranslate::trans($title, "en");
            $description = GoogleTranslate::trans($description, "en");

            $event->translateOrNew('en')->title = $title;
            $event->translateOrNew('en')->description = $description;
            $event->slug = \Illuminate\Support\Str::slug($title);
            $event->save();
        }

        Schema::table('events', function (Blueprint $table) {
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
        Schema::dropIfExists('event_translations');
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
};
