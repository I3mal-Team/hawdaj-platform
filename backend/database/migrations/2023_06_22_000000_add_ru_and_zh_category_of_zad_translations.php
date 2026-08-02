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
        foreach (\App\Models\CategoryOfZad::all() as $categoryOfZad) {
            $name = $categoryOfZad->translate("en")->name;
            $notes = $categoryOfZad->translate("en")->notes;

            $name = \Stichoza\GoogleTranslate\GoogleTranslate::trans($name, "ru");
            $notes = $notes ? \Stichoza\GoogleTranslate\GoogleTranslate::trans($notes, "ru") : "";
            $categoryOfZad->translateOrNew('ru')->name = $name;
            $categoryOfZad->translateOrNew('ru')->notes = $notes;

            $name = \Stichoza\GoogleTranslate\GoogleTranslate::trans($name, "zh");
            $notes = $notes ? \Stichoza\GoogleTranslate\GoogleTranslate::trans($notes, "zh") : "";
            $categoryOfZad->translateOrNew('zh')->name = $name;
            $categoryOfZad->translateOrNew('zh')->notes = $notes;
            $categoryOfZad->save();
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
