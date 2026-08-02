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
        foreach (\App\Models\Place::all() as $place) {
            $title = $place->translate("en")->title;
            $description = $place->translate("en")->description;

            $title = \Stichoza\GoogleTranslate\GoogleTranslate::trans($title, "ru");
            $description = \Stichoza\GoogleTranslate\GoogleTranslate::trans($description, "ru");
            $place->translateOrNew('ru')->title = $title;
            $place->translateOrNew('ru')->description = $description;

            $title = \Stichoza\GoogleTranslate\GoogleTranslate::trans($title, "zh");
            $description = $description ? \Stichoza\GoogleTranslate\GoogleTranslate::trans($description, "zh") : "";
            $place->translateOrNew('zh')->title = $title;
            $place->translateOrNew('zh')->description = $description;
            $place->save();
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
        Schema::dropIfExists('city_translations');
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
};
