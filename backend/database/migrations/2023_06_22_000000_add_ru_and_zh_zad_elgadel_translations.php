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
        foreach (\App\Models\ZadElgadel::all() as $zadElgadel) {
            $title = $zadElgadel->translate("en")->title;
            $description = $zadElgadel->translate("en")->description;

            $title = \Stichoza\GoogleTranslate\GoogleTranslate::trans($title, "ru");
            $description = \Stichoza\GoogleTranslate\GoogleTranslate::trans($description, "ru");
            $zadElgadel->translateOrNew('ru')->title = $title;
            $zadElgadel->translateOrNew('ru')->description = $description;

            $title = \Stichoza\GoogleTranslate\GoogleTranslate::trans($title, "zh");
            $description = $description ? \Stichoza\GoogleTranslate\GoogleTranslate::trans($description, "zh") : "";
            $zadElgadel->translateOrNew('zh')->title = $title;
            $zadElgadel->translateOrNew('zh')->description = $description;
            $zadElgadel->save();
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
