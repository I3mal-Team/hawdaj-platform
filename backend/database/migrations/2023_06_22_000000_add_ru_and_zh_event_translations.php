<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach (\App\Models\Event::all() as $event) {
            $title = $event->translate("en")->title;
            $description = $event->translate("en")->description;
            $title = \Stichoza\GoogleTranslate\GoogleTranslate::trans($title, "ru");
            $description = \Stichoza\GoogleTranslate\GoogleTranslate::trans($description, "ru");
            $event->translateOrNew('ru')->title = $title;
            $event->translateOrNew('ru')->description = $description;
            $title = \Stichoza\GoogleTranslate\GoogleTranslate::trans($title, "zh");
            $description = $description ? \Stichoza\GoogleTranslate\GoogleTranslate::trans($description, "zh") : "";
            $event->translateOrNew('zh')->title = $title;
            $event->translateOrNew('zh')->description = $description;
            $event->save();
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
